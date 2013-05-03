<?php

namespace DI\Definition\Configuration;

use DI\Definition\Configuration\ServiceDefinition;

interface ConfigurationDefinition {
	/**
	 * @param string $serviceId
	 * @return boolean
	 */
	public function serviceExists($serviceId);

	/**
	 * @param string $serviceId
	 * @return ServiceDefinition
	 */
	public function getServiceDefinition($serviceId);

	/**
	 * @return ServiceDefinition[]
	 */
	public function getServiceDefinitions();

	/**
	 * @param string $serviceId
	 * @param ServiceDefinition $proxyServiceDefinition
	 * @return void
	 */
	public function replaceServiceDefinition($serviceId, ServiceDefinition $proxyServiceDefinition);
}
