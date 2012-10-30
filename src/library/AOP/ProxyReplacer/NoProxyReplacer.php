<?php

namespace AOP\ProxyReplacer;

class NoProxyReplacer implements \AOP\ProxyReplacer {

	/**
	 * @param \DI\Definition\ConfigurationDefinition $configurationDefinition
	 * @return \DI\Definition\ConfigurationDefinition
	 */
	public function replaceProxies(\DI\Definition\ConfigurationDefinition $configurationDefinition) {
		return $configurationDefinition;
	}
}
