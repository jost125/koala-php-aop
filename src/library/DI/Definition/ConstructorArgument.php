<?php

namespace DI\Definition;

use DI\Container;

interface ConstructorArgument {
	/**
	 * @param Container $container
	 * @return mixed
	 */
	public function getValue(Container $container);
}
