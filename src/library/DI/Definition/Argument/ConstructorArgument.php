<?php

namespace DI\Definition\Argument;

use DI\Container;

interface ConstructorArgument {
	/**
	 * @param Container $container
	 * @return mixed
	 */
	public function getValue(Container $container);
}
