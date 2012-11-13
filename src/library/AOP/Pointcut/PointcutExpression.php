<?php

namespace AOP\Pointcut;

class PointcutExpression {

	private $expression;

	public function __construct($expression) {
		$this->expression = $expression;
	}
}
