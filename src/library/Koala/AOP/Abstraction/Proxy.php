<?php

namespace Koala\AOP\Abstraction;

use Koala\Collection\IMap;
use Koala\DI\Definition\Configuration\ServiceDefinition;

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
