<?php

namespace Koala\AOP\Pointcut;

class PointcutExpression {

	private $expression;

	public function __construct($expression) {
		$this->expression = $expression;
	}

	public function getExpression() {
		return $this->expression;
	}
}
