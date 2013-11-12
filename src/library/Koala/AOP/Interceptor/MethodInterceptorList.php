<?php

namespace Koala\AOP\Interceptor;

use Exception;
use Koala\AOP\Joinpoint;

class MethodInterceptorList {

	private $beforeInterceptors;
	private $aroundInterceptors;
	private $afterInterceptors;
	private $afterReturningInterceptors;
	private $afterThrowingInterceptors;

	/**
	 * @param Interceptor[] $beforeInterceptors
	 * @param Interceptor[] $aroundInterceptors
	 * @param Interceptor[] $afterInterceptors
	 * @param Interceptor[] $afterReturningInterceptors
	 * @param Interceptor[] $afterThrowingInterceptors
	 */
	public function __construct(
		array $beforeInterceptors,
		array $aroundInterceptors,
		array $afterInterceptors,
		array $afterReturningInterceptors,
		array $afterThrowingInterceptors
	) {
		$this->beforeInterceptors = $beforeInterceptors;
		$this->aroundInterceptors = $aroundInterceptors;
		$this->afterInterceptors = $afterInterceptors;
		$this->afterReturningInterceptors = $afterReturningInterceptors;
		$this->afterThrowingInterceptors = $afterThrowingInterceptors;
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

	public function interceptAfter(Joinpoint $joinpoint) {
		foreach ($this->afterInterceptors as $afterInterceptor) {
			$afterInterceptor->invoke($joinpoint);
		}
	}

	public function interceptAfterReturning(Joinpoint $joinpoint, $result) {
		foreach ($this->afterReturningInterceptors as $afterReturningInterceptor) {
			$afterReturningInterceptor->invokeWithResult($joinpoint, $result);
		}
	}

	public function interceptAfterThrowing(Joinpoint $joinpoint, Exception $exception) {
		if (!count($this->afterInterceptors)) {
			throw $exception;
		}
		foreach ($this->afterInterceptors as $afterInterceptor) {
			$afterInterceptor->invokeWithException($joinpoint, $exception);
		}
	}

}
