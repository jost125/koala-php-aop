<?php

namespace Koala\AOP\Proxy;

use Koala\DI\Definition\Configuration\ConfigurationDefinition;

class NoProxyReplacer implements ProxyReplacer {

	/**
	 * @param ConfigurationDefinition $configurationDefinition
	 * @return ConfigurationDefinition
	 */
	public function replaceProxies(ConfigurationDefinition $configurationDefinition) {
		return $configurationDefinition;
	}
}
