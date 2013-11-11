<?php

namespace AOP\Abstraction;

class Advice {

	private $pointcut;
	private $interceptingMethod;

	public function __construct(Pointcut $pointcut, InterceptingMethod $interceptingMethod) {
		$this->pointcut = $pointcut;
		$this->interceptingMethod = $interceptingMethod;
	}

	/**
	 * @return Pointcut
	 */
	public function getPointcut() {
		return $this->pointcut;
	}

	/**
	 * @return InterceptingMethod
	 */
	public function getInterceptingMethod() {
		return $this->interceptingMethod;
	}
}
