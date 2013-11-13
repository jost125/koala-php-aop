<?php

namespace Example\Aspect;

use Example\Response\JsonResponse;
use Koala\AOP\Aspect;
use Koala\AOP\Around;
use Koala\AOP\Joinpoint;

/**
 * @Aspect
 */
class JsonResponseConvertor {

	/**
	 * @Around("execution(public \Example\Controller*::*Action(..))")
	 */
	public function convert(Joinpoint $joinpoint) {
		$result = $joinpoint->proceed();
		return new JsonResponse($result);
	}
}
