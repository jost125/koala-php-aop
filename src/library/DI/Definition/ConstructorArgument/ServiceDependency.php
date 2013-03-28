<?php

namespace DI\Definition\ConstructorArgument;

use DI\Container;
use DI\Definition\ConstructorArgument;

class ServiceDependency implements ConstructorArgument {

	private $serviceId;

	function __construct($serviceId) {
		$this->serviceId = $serviceId;
	}

	public function getValue(Container $container) {
		return $container->getService($this->serviceId);
	}
}
