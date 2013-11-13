<?php

namespace Example\Aspect;

use Koala\AOP\Aspect;
use Koala\AOP\Around;
use Koala\AOP\Joinpoint;
use Example\Logger;

/**
 * @Aspect
 */
class NameWrapper {
	/**
	 * @Around("execution(public \Example\Controller\HiController::sayHiAction(var))")
	 */
	public function wrapName(Joinpoint $joinpoint) {
		$joinpoint->setArgument('<b>' . $joinpoint->getArgument(0) . '</b>', 0);
		$result = $joinpoint->proceed();

		return $result;
	}
}
