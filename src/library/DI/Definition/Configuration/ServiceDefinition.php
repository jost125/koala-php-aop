<?php

namespace DI\Definition\Configuration;

use DI\Definition\Argument\SetupMethod;
use DI\Definition\Argument\WiringArgument;
use ReflectionClass;

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
	 * @return boolean
	 */
	public function hasSetupMethods();

	/**
	 * @return SetupMethod[]
	 */
	public function getSetupMethods();

	/**
	 * @return string
	 */
	public function getClassName();

	/**
	 * @return string
	 */
	public function getServiceId();

	/**
	 * @return ReflectionClass
	 */
	public function getClassReflection();
}
