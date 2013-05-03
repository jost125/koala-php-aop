<?php

namespace DI\Definition\Argument;

use DI\Container;
use DI\Definition\Argument\ConstructorArgument;

class ServiceDependency implements ConstructorArgument {

	private $serviceId;

	function __construct($serviceId) {
		$this->serviceId = $serviceId;
	}

	public function getValue(Container $container) {
		return $container->getService($this->serviceId);
	}
}
