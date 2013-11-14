<?php

namespace Koala\AOP\Pointcut\Parser;

use Koala\AOP\Pointcut\Parser\AST\Element\AnnotationClassExpression;
use Koala\AOP\Pointcut\Parser\AST\Element\AnyArguments;
use Koala\AOP\Pointcut\Parser\AST\Element\Argument;
use Koala\AOP\Pointcut\Parser\AST\Element\ArgumentsExpression;
use Koala\AOP\Pointcut\Parser\AST\Element\ClassExpression;
use Koala\AOP\Pointcut\Parser\AST\Element\MethodAnnotatedPointcut;
use Koala\AOP\Pointcut\Parser\AST\Element\MethodExpression;
use Koala\AOP\Pointcut\Parser\AST\Element\Modifier;
use Koala\AOP\Pointcut\Parser\AST\Element\NoArguments;
use Koala\AOP\Pointcut\Parser\AST\Element\ExecutionPointcut;
use Koala\AOP\Pointcut\Parser\AST\Element\PointcutExpression;
use Koala\AOP\Pointcut\Parser\AST\Element\PointcutExpressionGroupEnd;
use Koala\AOP\Pointcut\Parser\AST\Element\PointcutExpressionGroupStart;
use Koala\AOP\Pointcut\Parser\AST\Element\PointcutOperator;
use Koala\AOP\Pointcut\Parser\AST\Element\PointcutType;
use Koala\IO\Stream\InputStream;

class Lexer {

	private $stream;

	public function __construct(InputStream $stream) {
		$this->stream = $stream;
	}

	public function buildTree() {
		$pointcutExpression = $this->pointcutExpression();
		if ($this->stream->peek() !== InputStream::EOF) {
			$this->throwUnexpectedChar($this->stream->peek());
		}
		return $pointcutExpression;
	}

	private function pointcutExpression() {
		$pointcutExpression = new PointcutExpression();
		switch ($this->stream->peek()) {
			case 'e':
			case 'm':
				$pointcutExpression->addElement($this->pointcut());
				if ($this->matchWs($this->stream->peek())) {
					$this->skipWs();
					if (in_array($this->stream->peek(), array('a', 'o'))) {
						$pointcutExpression->addElement($this->pointcutOperator());
						$this->ws();
						$this->skipWs();
						$pointcutExpression->addElement($this->pointcutExpression());
					}
				}
				break;
			case '(':
				$this->skipChar('(');
				$pointcutExpression->addElement(new PointcutExpressionGroupStart(''));
				$this->skipWs();
				$pointcutExpression->addElement($this->pointcutExpression());
				$this->skipWs();
				$this->skipChar(')');
				$pointcutExpression->addElement(new PointcutExpressionGroupEnd(''));
		}

		return $pointcutExpression;
	}

	private function pointcut() {
		$c = $this->stream->peek();
		if ($c == 'e') {
			$pointcut = new ExecutionPointcut();
		} else {
			$pointcut = new MethodAnnotatedPointcut();
		}

		$pointcut->addElement($this->pointcutType());
		$this->skipWs();
		$this->skipChar('(');
		$this->skipWs();

		if ($c == 'e') {
			$pointcut->addElement($this->modifier());
			$this->ws();
			$this->skipWs();
			$pointcut->addElement($this->classExpression());
			$this->skipWord('::');
			$pointcut->addElement($this->methodExpression());
			$this->skipChar('(');
			$this->skipWs();

			$c = $this->stream->peek();
			if (in_array($c, array('.', 'v', '$', '\\'))) {
				$pointcut->addElement($this->argumentsExpression());
				$this->skipWs();
			} else {
				$pointcut->addElement(new NoArguments(''));
			}

			$this->skipChar(')');
		} else if ($c == 'm') {
			$pointcut->addElement($this->annotationClassExpression());
		}
		$this->skipWs();
		$this->skipChar(')');

		return $pointcut;
	}

	private function pointcutType() {
		$this->stream->startRecording();
		$c = $this->stream->peek();
		if ($c == 'e') {
			$this->skipWord('execution');
		} else {
			$this->skipWord('methodAnnotated');
		}
		$this->stream->stopRecording();
		return new PointcutType($this->stream->getRecord());
	}

	private function pointcutOperator() {
		$this->stream->startRecording();
		switch ($this->stream->peek()) {
			case 'a': $this->skipWord('and'); break;
			case 'o': $this->skipWord('or'); break;
		}
		$this->stream->stopRecording();

		return new PointcutOperator($this->stream->getRecord());
	}

	private function modifier() {
		$this->stream->startRecording();
		switch($this->stream->peek()) {
			case 'p':
				$this->skipChar('p');
				switch ($this->stream->peek()) {
					case 'r':
						$this->skipChar('r');
						switch ($this->stream->peek()) {
							case 'i': $this->skipWord('ivate'); break;
							case 'o': $this->skipWord('otected'); break;
						}
						break;
					case 'u': $this->skipWord('ublic'); break;
				}
				break;
			case '*': $this->skipChar('*'); break;
		}
		$this->stream->stopRecording();
		return new Modifier($this->stream->getRecord());
	}

	private function annotationClassExpression() {
		$this->stream->startRecording();
		do {
			switch($this->stream->peek()) {
				case '*':
					$this->skipChar('*');
					if ($this->matchIdBegin($this->stream->peek())) {
						$this->id();
					}
					break;
				case '\\':
					$this->skipChar('\\');
					$this->id();
					break;
				default:
					$this->throwUnexpectedChar($this->stream->read());
			}
		} while(in_array($this->stream->peek(), array('*', '\\')));
		$this->stream->stopRecording();
		return new AnnotationClassExpression($this->stream->getRecord());
	}

	private function classExpression() {
		$this->stream->startRecording();
		do {
			switch($this->stream->peek()) {
				case '*':
					$this->skipChar('*');
					if ($this->matchIdBegin($this->stream->peek())) {
						$this->id();
					}
					break;
				case '\\':
					$this->skipChar('\\');
					$this->id();
					break;
				default:
					$this->throwUnexpectedChar($this->stream->read());
			}
		} while(in_array($this->stream->peek(), array('*', '\\')));
		$this->stream->stopRecording();
		return new ClassExpression($this->stream->getRecord());
	}

	private function methodExpression() {
		$this->stream->startRecording();
		$c = $this->stream->peek();
		while ($c === '*' || $this->matchIdBegin($c)) {
			if ($c === '*') {
				$this->skipChar('*');
			} else if ($this->matchIdBegin($c)) {
				$this->id();
			}
			$c = $this->stream->peek();
		}
		$this->stream->stopRecording();
		return new MethodExpression($this->stream->getRecord());
	}

	private function argumentsExpression() {
		$argumentsExpression = new ArgumentsExpression();
		if($this->stream->peek() === '.') {
			$this->skipWord('..');
			$argumentsExpression->addElement(new AnyArguments(''));
		} else {
			$argumentsExpression->addElement($this->argument());
			while ($this->stream->peek() === ',' || $this->matchWs($this->stream->peek())) {
				if ($this->matchWs($this->stream->peek())) {
					$this->skipWs();
				}
				if ($this->stream->peek() === ',') {
					$this->skipChar(',');
					$this->skipWs();
					$argumentsExpression->addElement($this->argument());
				}
			}
		}

		return $argumentsExpression;
	}

	private function argument() {
		$this->stream->startRecording();
		switch ($this->stream->peek()) {
			case 'v': $this->skipWord('var'); break;
			case '$': $this->skipChar('$'); $this->id(); break;
			case '\\':
				while ($this->stream->peek() === '\\') {
					$this->skipChar('\\');
					$this->id();
				}
				$this->ws();
				$this->skipWs();
				$this->skipChar('$');
				$this->id();
				break;
		}
		$this->stream->stopRecording();
		return new Argument($this->stream->getRecord());
	}

	private function ws() {
		$c = $this->stream->read();
		while (!$this->matchWs($c)) {
			$this->throwUnexpectedChar($c);
		}
	}

	private function matchIdBegin($c) {
		return preg_match('~[a-zA-Z_]~', $c);
	}

	private function matchWs($c) {
		return in_array($c, array(' ', "\t", "\r", "\n"));
	}

	private function skipChar($char) {
		$c = $this->stream->read();
		if ($c !== $char) {
			$this->throwUnexpectedChar($c);
		}
	}

	private function skipWs() {
		while ($this->matchWs($this->stream->peek())) {
			$this->stream->read();
		}
	}

	private function skipWord($string) {
		$strlen = strlen($string);
		for ($i = 0; $i < $strlen; $i++) {
			$this->skipChar($string[$i]);
		}
	}

	private function id() {
		$c = $this->stream->read();
		if (!$this->matchIdBegin($c)) {
			$this->throwUnexpectedChar($c);
		}
		while (preg_match('~[a-zA-Z0-9_]~', $this->stream->peek())) {
			$this->stream->read();
		}
	}

	private function throwUnexpectedChar($c) {
		throw new UnexpectedCharException('Unexpected ' . ($c === InputStream::EOF ? 'EOF' : 'char \'' . $c . '\'') . ' at position ' . $this->stream->pointer());
	}

}
