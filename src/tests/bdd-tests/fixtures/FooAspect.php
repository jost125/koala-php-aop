<?php

class FooAspect {

	/**
	 * @Around("execution(public \SimpleProxyFinderTest\FooService::*(..))")
	 */
	public function fooAdvice() {

	}

}
