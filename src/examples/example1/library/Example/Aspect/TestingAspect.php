<?php

namespace Example\Aspect;

use Koala\AOP\Aspect;
use Koala\AOP\Around;
use Koala\AOP\After;
use Koala\AOP\AfterReturning;
use Koala\AOP\AfterThrowing;
use Koala\AOP\Before;
use Koala\AOP\Joinpoint;
use Example\Logger;

/**
 * @Aspect
 */
class TestingAspect {

	/**
	 * @Before("execution(* *::*(..))")
	 */
	public function before(Joinpoint $joinpoint) {
		var_dump('before execution ' . $joinpoint->getMethodName() . ' in ' . $joinpoint->getClassName());
	}

	/**
	 * @After("execution(* *::*(..))")
	 */
	public function after(Joinpoint $joinpoint) {
		var_dump('after execution ' . $joinpoint->getMethodName() . ' in ' . $joinpoint->getClassName());
	}

	/**
	 * @AfterReturning("execution(* *::*(..))")
	 */
	public function afterReturning(Joinpoint $joinpoint) {
		var_dump('after-returning execution ' . $joinpoint->getMethodName() . ' in ' . $joinpoint->getClassName());
	}

	/**
	 * @AfterThrowing("execution(* *::*(..))")
	 */
	public function afterThrowing(Joinpoint $joinpoint) {
		var_dump('after-throwing execution ' . $joinpoint->getMethodName() . ' in ' . $joinpoint->getClassName());
	}

	/**
	 * @Around("execution(* *::*(..))")
	 */
	public function around(Joinpoint $joinpoint) {
		var_dump('around-begin execution ' . $joinpoint->getMethodName() . ' in ' . $joinpoint->getClassName());
		$result = $joinpoint->proceed();
		var_dump('around-end execution ' . $joinpoint->getMethodName() . ' in ' . $joinpoint->getClassName());
		return $result;
	}
}
