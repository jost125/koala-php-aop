<?php

namespace AOP\Abstraction;

use DI\Definition\ServiceDefinition;

class Proxy {

	private $targetDefinition;
	private $joinpoints;
	private $advice;

	/**
	 * @param Advice $advice
	 * @param Joinpoint[] $joinpoints
	 * @param ServiceDefinition $targetDefinition
	 */
	public function __construct(Advice $advice, array $joinpoints, ServiceDefinition $targetDefinition) {
		$this->advice = $advice;
		$this->joinpoints = $joinpoints;
		$this->targetDefinition = $targetDefinition;
	}

	/**
	 * @return Advice
	 */
	public function getAdvice() {
		return $this->advice;
	}

	/**
	 * @return Joinpoint[]
	 */
	public function getJoinpoints() {
		return $this->joinpoints;
	}

	/**
	 * @return ServiceDefinition
	 */
	public function getTargetDefinition() {
		return $this->targetDefinition;
	}

}
