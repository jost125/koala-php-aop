<?php

namespace AOP\Abstraction;

use AOP\Pointcut\PointcutExpression;

class Pointcut {

	private $pointcutExpression;

	public function __construct(PointcutExpression $pointcutExpression) {
		$this->pointcutExpression = $pointcutExpression;
	}

	/**
	 * @return PointcutExpression
	 */
	public function getPointcutExpression() {
		return $this->pointcutExpression;
	}
}
