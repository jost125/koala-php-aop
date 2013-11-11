<?php

namespace Koala\DI\Definition\Configuration;

interface ConfigurationDefinition {
	/**
	 * @param string $serviceId
	 * @return boolean
	 */
	public function serviceExists($serviceId);

	/**
	 * @param string $parameterId
	 * @return boolean
	 */
	public function hasParameter($parameterId);

	/**
	 * @param string $parameterId
	 * @return mixed
	 */
	public function getParameter($parameterId);

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
