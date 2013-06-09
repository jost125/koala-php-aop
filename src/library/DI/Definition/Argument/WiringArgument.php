<?php

namespace DI\Definition\Argument;

use DI\Container;

interface WiringArgument {
	/**
	 * @param Container $container
	 * @return mixed
	 */
	public function getValue(Container $container);
}
