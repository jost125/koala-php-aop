<?php

namespace Koala\DI\Definition\Argument;

use Koala\DI\Container;

interface WiringArgument {
	/**
	 * @param Container $container
	 * @return mixed
	 */
	public function getValue(Container $container);
}
