<?php

namespace DI\Definition;

interface ConstructorArgument {
	/**
	 * @param \DI\Container $container
	 * @return mixed
	 */
	public function getValue(\DI\Container $container);
}
