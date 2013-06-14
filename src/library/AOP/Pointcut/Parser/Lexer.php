<?php

namespace AOP\Pointcut\Parser;

class Lexer {

	private $stream;

	public function __construct(Stream $stream) {
		$this->stream = $stream;
	}

	public function parseTokens() {
		$this->pointcutExpression();
		if ($this->stream->peek() !== Stream::EOF) {
			$this->throwUnexpectedChar($this->stream->peek());
		}
	}

	private function pointcutExpression() {
		switch ($this->stream->peek()) {
			case 'e':
				$this->pointcut();
				if ($this->matchWs($this->stream->peek())) {
					$this->skipWs();
					if (in_array($this->stream->peek(), array('a', 'o'))) {
						$this->pointcutOperator();
						$this->ws();
						$this->skipWs();
						$this->pointcutExpression();
					}
				}
				break;
			case '(':
				$this->skipChar('(');
				$this->skipWs();
				$this->pointcutExpression();
				$this->skipWs();
				$this->skipChar(')');
		}
	}

	private function pointcut() {
		$this->joinpointType();
		$this->skipWs();
		$this->skipChar('(');
		$this->skipWs();
		$this->modifier();
		$this->ws();
		$this->skipWs();
		$this->classExpression();
		$this->skipWord('::');
		$this->methodExpression();
		$this->skipChar('(');
		$this->skipWs();

		$c = $this->stream->peek();
		if (in_array($c, array('.', 'v', '$')) || $this->matchIdBegin($c)) {
			$this->argumentsExpression();
			$this->skipWs();
		}

		$this->skipChar(')');
		$this->skipWs();
		$this->skipChar(')');
	}

	private function joinpointType() {
		$this->skipWord('execution');
	}

	private function pointcutOperator() {
		switch ($this->stream->peek()) {
			case 'a': $this->skipWord('and'); break;
			case 'o': $this->skipWord('or'); break;
		}
	}

	private function modifier() {
		switch($this->stream->peek()) {
			case 'p':
				$this->skipChar('p');
				switch ($this->stream->peek()) {
					case 'r':
						$this->skipChar('r');
						switch ($this->stream->peek()) {
							case 'i': $this->skipWord('ivate'); break;
							case 'o': $this->skipWord('etected'); break;
						}
						break;
					case 'u': $this->skipWord('ublic'); break;
				}
				break;
			case '*': $this->skipChar('*'); break;
		}
	}

	private function classExpression() {
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
	}

	private function methodExpression() {
		$c = $this->stream->peek();
		while ($c === '*' || $this->matchIdBegin($c)) {
			if ($c === '*') {
				$this->skipChar('*');
			} else if ($this->matchIdBegin($c)) {
				$this->id();
			}
			$c = $this->stream->peek();
		}
	}

	private function argumentsExpression() {
		if($this->stream->peek() === '.') {
			$this->skipWord('..');
		} else {
			$this->argument();
			while ($this->stream->peek() === ',' || $this->matchWs($this->stream->peek())) {
				if ($this->matchWs($this->stream->peek())) {
					$this->skipWs();
				}
				if ($this->stream->peek() === ',') {
					$this->skipChar(',');
					$this->skipWs();
					$this->argument();
				}
			}
		}
	}

	private function argument() {
		switch ($this->stream->peek()) {
			case 'v': $this->skipWord('var'); break;
			case '$': $this->skipChar('$'); $this->id(); break;
		}
		if ($this->matchIdBegin($this->stream->peek())) {
			$this->id();
			$this->ws();
			$this->skipWs();
			$this->skipChar('$');
			$this->id();
		}
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
		throw new UnexpectedCharException('Unexpected ' . ($c === Stream::EOF ? 'EOF' : 'char \'' . $c . '\'') . ' at position ' . $this->stream->pointer());
	}

}
