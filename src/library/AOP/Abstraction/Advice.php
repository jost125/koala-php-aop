<?php

namespace AOP\Abstraction;

class Advice {

	private $pointcut;

	public function __construct(Pointcut $pointcut) {
		$this->pointcut = $pointcut;
	}

	/**
	 * @return Pointcut
	 */
	public function getPointcut() {
		return $this->pointcut;
	}
}
