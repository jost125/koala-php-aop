<?php

namespace Koala\AOP\Pointcut\Compiler;

use Koala\AOP\Pointcut\Parser\AST\Element\AnyArguments;
use Koala\AOP\Pointcut\Parser\AST\Element\Argument;
use Koala\AOP\Pointcut\Parser\AST\Element\ArgumentsExpression;
use Koala\AOP\Pointcut\Parser\AST\Element\ClassExpression;
use Koala\AOP\Pointcut\Parser\AST\Element\MethodExpression;
use Koala\AOP\Pointcut\Parser\AST\Element\Modifier;
use Koala\AOP\Pointcut\Parser\AST\Element\NoArguments;
use Koala\AOP\Pointcut\Parser\AST\Element\Pointcut;
use Koala\AOP\Pointcut\Parser\AST\Element\PointcutExpression;
use Koala\AOP\Pointcut\Parser\AST\Element\PointcutExpressionGroupEnd;
use Koala\AOP\Pointcut\Parser\AST\Element\PointcutExpressionGroupStart;
use Koala\AOP\Pointcut\Parser\AST\Element\PointcutOperator;
use Koala\AOP\Pointcut\Parser\AST\Element\PointcutType;
use Koala\AOP\Pointcut\Parser\AST\ElementVisitor;

class CompileMethodMatchVisitor implements ElementVisitor {

	/** @var Modifier */
	private $modifier;
	private $compiled;

	/** @var Argument[] */
	private $arguments;
	private $canHaveArgument;

	public function __construct() {
		$this->compiled = '';
		$this->arguments = array();
		$this->canHaveArgument = true;
		$this->modifier = null;
	}

	public function acceptAnyArguments(AnyArguments $anyArguments) {
	}

	public function acceptArgument(Argument $argument) {
		$this->arguments[] = $argument;
	}

	public function acceptPointcutExpression(PointcutExpression $pointcutExpression) {
	}

	public function acceptArgumentsExpression(ArgumentsExpression $argumentsExpression) {
		if (!$this->canHaveArgument) {
			$this->compiled .= ' && $reflectionMethod->getNumberOfParameters() === 0';
		} else {
			if (count($this->arguments)) {
				$this->compiled .= ' && $reflectionMethod->getNumberOfParameters() === ' . count($this->arguments);
			}
			foreach ($this->arguments as $key => $argument) {
				$value = trim($argument->getValue());
				$exploded = preg_split('~\s+~', $value);
				if (count($exploded) === 2) {
					$this->compiled .= ' && $this->getMethodArgument(' . $key . ')->getClass() === \'' . str_replace('\\', '\\\\', $exploded[0]) . '\'';
				}
			}
		}
	}

	public function acceptClassExpression(ClassExpression $classExpression) {
		$this->compiled .= 'preg_match(\'~' . $this->prepareRegex(ltrim($classExpression->getValue(), '\\')) . '~\', $reflectionMethod->getDeclaringClass()->getName())';
	}

	public function acceptMethodExpression(MethodExpression $methodExpression) {
		$this->compiled .= ' && preg_match(\'~' . $this->prepareRegex($methodExpression->getValue()) . '~\', $reflectionMethod->getName())';
		if ($this->modifier->getValue() !== '*') {
			$this->compiled .= ' && $reflectionMethod->is' . ucfirst($this->modifier->getValue()) . '()';
		}
	}

	public function acceptModifier(Modifier $modifier) {
		if ($modifier->getValue() == 'private') {
			throw new NotSupportedModifierException('private modifier is not yet supported.');
		}
		$this->modifier = $modifier;
	}

	public function acceptNoArguments(NoArguments $noArguments) {
		$this->canHaveArgument = false;
	}

	public function acceptPointcut(Pointcut $pointcut) {
		$this->compiled .= ')';
		$this->arguments = array();
		$this->canHaveArgument = true;
		$this->modifier = null;
	}

	public function acceptPointcutExpressionGroupStart(PointcutExpressionGroupStart $pointcutExpressionGroupStart) {
		$this->compiled .= '(';
	}

	public function acceptPointcutExpressionGroupEnd(PointcutExpressionGroupEnd $pointcutExpressionGroupEnd) {
		$this->compiled .= ')';
	}

	public function acceptPointcutOperator(PointcutOperator $pointcutOperator) {
		$this->compiled .= $pointcutOperator->getValue() === 'and' ? ' && ' : ' || ';
	}

	public function acceptPointcutType(PointcutType $pointcutType) {
		$this->compiled .= '(';
	}

	public function getCompiled() {
		return $this->compiled;
	}

	private function prepareRegex($expression) {
		return '^' . str_replace('\\', '\\\\\\\\', str_replace('*', '.*?', $expression)) . '$';
	}
}
