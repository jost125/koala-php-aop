<?php

namespace AOP\Proxy;

use AOP\Proxy\ProxyReplacer;
use DI\Definition\Configuration\ConfigurationDefinition;

class NoProxyReplacer implements ProxyReplacer {

	/**
	 * @param ConfigurationDefinition $configurationDefinition
	 * @return ConfigurationDefinition
	 */
	public function replaceProxies(ConfigurationDefinition $configurationDefinition) {
		return $configurationDefinition;
	}
}
