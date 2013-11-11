<?php

namespace DI\Definition\Argument;

use DI\Container;
use DI\Definition\Argument\WiringArgument;

class ServiceDependency implements WiringArgument {

	private $serviceId;

	public function __construct($serviceId) {
		$this->serviceId = $serviceId;
	}

	public function getValue(Container $container) {
		return $container->getService($this->serviceId);
	}
}
