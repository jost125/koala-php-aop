<?php

namespace Koala\AOP\Pointcut;

/**
 * Usage:
 * @\AOP\Pointcut("execution([modifier] \My\Package\MyClass::myMethod(ArgumentType, ArgumentType2))")
 *
 * Examples:
 * @\AOP\Pointcut("execution(public *\MyClass::*(..))")	Any method of MyClass in any package with any arguments
 * @\AOP\Pointcut("execution(public \My\Package\*::*(..))")	Any method of any class in package \My\Package with any arguments
 * @\AOP\Pointcut("execution(public \My\*\*::*(..))")	Any method of any class in any subpackage in package \My with any arguments
 * @\AOP\Pointcut("execution(public \My\Package\MyClass::myMethod(var, var))")	a method \My\Package\MyClass::myMethod with unspecified two arguments
 */
class PointcutExpression {

	private $expression;

	public function __construct($expression) {
		$this->expression = $expression;
	}

	public function getExpression() {
		return $this->expression;
	}
}
