<?php

namespace AOP\ProxyReplacer;

use AOP\ProxyReplacer;
use DI\Definition\ConfigurationDefinition;

class NoProxyReplacer implements ProxyReplacer {

	/**
	 * @param ConfigurationDefinition $configurationDefinition
	 * @return ConfigurationDefinition
	 */
	public function replaceProxies(ConfigurationDefinition $configurationDefinition) {
		return $configurationDefinition;
	}
}
