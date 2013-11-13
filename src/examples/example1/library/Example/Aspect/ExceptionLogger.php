<?php

namespace Example\Aspect;

use Example\Logger;
use Exception;
use Koala\AOP\Aspect;
use Koala\AOP\AfterThrowing;
use Koala\AOP\Joinpoint;

/**
 * @Aspect
 */
class ExceptionLogger {

	private $logger;

	function __construct(Logger $logger) {
		$this->logger = $logger;
	}

	/**
	 * @AfterThrowing("execution(* \Example\Controller*::*(..))")
	 */
	public function logException(Joinpoint $joinpoint, Exception $exception) {
		$this->logger->log($exception->getMessage(), 'exception');
	}

}
