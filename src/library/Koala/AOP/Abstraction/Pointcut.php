<?php

namespace Koala\AOP\Abstraction;

use Koala\AOP\Pointcut\PointcutExpression;

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
