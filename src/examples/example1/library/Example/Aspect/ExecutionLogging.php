<?php

namespace Example\Aspect;

use AOP\Joinpoint;
use AOP\Aspect;
use AOP\Around;
use Example\Logger;

/**
 * @Aspect
 */
class ExecutionLogging {

	private $logger;
	
	public function __construct(Logger $logger) {
		$this->logger = $logger;
	}

	/**
	 * @Around("execution(public \Example\Model\Facade\*::fetch*(..))")
	 */
	public function logExecution(Joinpoint $joinpoint) {
		$result = $joinpoint->proceed();

		$this->logger->log(
			sprintf('class: %s, method: %s, arguments: %s, returned: %s',
				$joinpoint->getClassName(),
				$joinpoint->getMethodName(),
				serialize($joinpoint->getArguments()),
				serialize($result)
			),
			'info'
		);

		return $result;
	}
}
