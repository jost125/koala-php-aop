<?php

namespace Koala\DI\Definition\Argument;

use Koala\DI\Container;

class ParameterArgument implements WiringArgument {

	private $parameterId;

	public function __construct($parameterId) {
		$this->parameterId = $parameterId;
	}

	public function getValue(Container $container) {
		return $container->getParameter($this->parameterId);
	}

}
