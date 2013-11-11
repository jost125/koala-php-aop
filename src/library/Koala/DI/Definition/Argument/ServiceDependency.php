<?php

namespace Koala\DI\Definition\Argument;

use Koala\DI\Container;

class ServiceDependency implements WiringArgument {

	private $serviceId;

	public function __construct($serviceId) {
		$this->serviceId = $serviceId;
	}

	public function getValue(Container $container) {
		return $container->getService($this->serviceId);
	}
}
