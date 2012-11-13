<?php

namespace AOP\Abstraction;

class Pointcut {

	private $pointcutExpression;

	public function __construct(\AOP\Pointcut\PointcutExpression $pointcutExpression) {
		$this->pointcutExpression = $pointcutExpression;
	}

	/**
	 * @return \AOP\Pointcut\PointcutExpression
	 */
	public function getPointcutExpression() {
		return $this->pointcutExpression;
	}
}
