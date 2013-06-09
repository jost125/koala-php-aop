<?php

namespace DI\Definition\Configuration;

use DI\Definition\Argument\WiringArgument;

interface ServiceDefinition {
	/**
	 * @return WiringArgument[]
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
