<?php

namespace AOP\Abstraction;

use Collection\IMap;
use DI\Definition\Configuration\ServiceDefinition;

class Proxy {

	private $joinpointsAdvices;
	private $targetDefinition;

	public function __construct(IMap $joinpointsAdvices, ServiceDefinition $targetDefinition) {
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
