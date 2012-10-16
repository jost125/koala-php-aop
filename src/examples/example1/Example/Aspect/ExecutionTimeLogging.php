<?php

namespace Example\Aspect;

/**
 * @\AOP\Aspect
 */
class ExecutionTimeLoggingAspect {

	private $logger;
	private $stopwatchFactory;
	
	public function __construct(\Example\Logger $logger, \Example\Stopwatch\StopwatchFactory $stopwatchFactory) {
		$this->logger = $logger;
		$this->stopwatchFactory = $stopwatchFactory;
	}

	/**
	 * @\AOP\Around("execution(public \Example\Model\Facade\*::fetch*(..))")
	 */
	public function logExecutionTime(\AOP\Joinpoint $joinpoint) {
		$stopwatch = $this->stopwatchFactory->createStopwatch();

		$stopwatch->start();
		$result = $joinpoint->proceed();

		$this->logger->log(sprintf('class: %s, method: %s, execution time: %s',
			$joinpoint->getClassName(),
			$joinpoint->getMethodName(),
			$stopwatch->stop()
		), 'info');

		return $result;
	}
}
