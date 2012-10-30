<?php

namespace DI\Definition\ConstructorArgument;

class ServiceDependency implements \DI\Definition\ConstructorArgument {

	private $serviceId;

	function __construct($serviceId) {
		$this->serviceId = $serviceId;
	}

	public function getValue(\DI\Container $container) {
		return $container->getService($this->serviceId);
	}
}
