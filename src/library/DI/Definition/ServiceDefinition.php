<?php

namespace DI\Definition;

interface ServiceDefinition {
	/**
	 * @return ConstructorArgument[]
	 */
	public function getConstructorArguments();

	/**
	 * @return \ReflectionClass
	 */
	public function getClassReflection();

	/**
	 * @return boolean
	 */
	public function hasConstructorArguments();
}
