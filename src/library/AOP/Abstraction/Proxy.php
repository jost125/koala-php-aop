<?php

namespace AOP\Abstraction;

use DI\Definition\Configuration\ServiceDefinition;
use SplObjectStorage;

class Proxy {

	private $joinpointsAdvices;
	private $targetDefinition;

	public function __construct(SplObjectStorage $joinpointsAdvices, ServiceDefinition $targetDefinition) {
		$this->joinpointsAdvices = $joinpointsAdvices;
		$this->targetDefinition = $targetDefinition;
	}

	public function getTargetDefinition() {
		return $this->targetDefinition;
	}

	public function getJoinpointsAdvices() {
		return $this->joinpointsAdvices;
	}

}
