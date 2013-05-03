<?php

namespace DI\Definition\Configuration;

use DI\Definition\Argument\ConstructorArgument;

interface ServiceDefinition {
	/**
	 * @return ConstructorArgument[]
	 */
	public function getConstructorArguments();

	/**
	 * @return boolean
	 */
	public function hasConstructorArguments();

	/**
	 * @return string
	 */
	public function getClassName();

	/**
	 * @return string
	 */
	public function getServiceId();
}
