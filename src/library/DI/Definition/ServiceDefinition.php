<?php

namespace DI\Definition;

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
