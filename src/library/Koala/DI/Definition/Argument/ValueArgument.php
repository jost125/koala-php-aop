<?php

namespace DI\Definition\Argument;

use DI\Container;

class ValueArgument implements WiringArgument {

	private $value;

	public function __construct($value) {
		$this->value = $value;
	}

	public function getValue(Container $container) {
		return $this->value;
	}

}
