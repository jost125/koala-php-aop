<?php

namespace Example\Aspect;

use Exception;
use Koala\AOP\Aspect;
use Koala\AOP\Around;
use Koala\AOP\Joinpoint;

/**
 * @Aspect
 */
class TransactionalHandler {

	/**
	 * @Around("methodAnnotated(*Transactional)")
	 */
	public function transaction(Joinpoint $joinpoint) {
		var_dump('begin');
		try {
			var_dump('executing ' . $joinpoint->getClassName() . '::' . $joinpoint->getMethodName());
			$returned = $joinpoint->proceed();
			var_dump('commit');
			return $returned;
		} catch (Exception $ex) {
			var_dump('rollback');
			throw $ex;
		}
	}

}
