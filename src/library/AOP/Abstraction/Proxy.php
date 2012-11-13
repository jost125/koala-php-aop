<?php

namespace AOP\Abstraction;

class Proxy {

	private $targetDefinition;
	private $joinpoints;
	private $advice;

	/**
	 * @param Advice $advice
	 * @param Joinpoint[] $joinpoints
	 * @param \DI\Definition\ServiceDefinition $targetDefinition
	 */
	public function __construct(Advice $advice, array $joinpoints, \DI\Definition\ServiceDefinition $targetDefinition) {
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
	 * @return \DI\Definition\ServiceDefinition
	 */
	public function getTargetDefinition() {
		return $this->targetDefinition;
	}

}
