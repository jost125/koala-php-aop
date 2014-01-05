<?php

namespace Koala\DI\Definition\Argument;

use Koala\DI\Container;

class ValueArgument implements WiringArgument {

	private $value;

	public function __construct($value) {
		$this->value = $value;
	}

	public function getValue(Container $container) {
		return $this->value;
	}

}
