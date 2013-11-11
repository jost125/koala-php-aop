<?php

namespace AOP\Interceptor;

use AOP\Joinpoint;

class MethodInterceptorList {

	private $beforeInterceptors;
	private $aroundInterceptors;
	private $afterInterceptors;

	/**
	 * @param Interceptor[] $beforeInterceptors
	 * @param Interceptor[] $aroundInterceptors
	 * @param Interceptor[] $afterInterceptors
	 */
	public function __construct(array $beforeInterceptors, array $aroundInterceptors, array $afterInterceptors) {
		$this->beforeInterceptors = $beforeInterceptors;
		$this->aroundInterceptors = $aroundInterceptors;
		$this->afterInterceptors = $afterInterceptors;
	}

	public function interceptBefore(Joinpoint $joinpoint) {
		foreach ($this->beforeInterceptors as $beforeInterceptor) {
			$beforeInterceptor->invoke($joinpoint);
		}
	}

	public function interceptAround(Joinpoint $joinpoint) {
		$joinpoint->setAroundInterceptors($this->aroundInterceptors);
		return $joinpoint->proceed();
	}

	public function interceptAfter($joinpoint, $result) {
		foreach ($this->afterInterceptors as $afterInterceptor) {
			$afterInterceptor->invoke($joinpoint, $result);
		}
	}

}
