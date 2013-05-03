<?php

namespace AOP\Proxy;

use DI\Definition\Configuration\ConfigurationDefinition;

interface ProxyReplacer {
	/**
	 * @param ConfigurationDefinition $configurationDefinition
	 * @return ConfigurationDefinition
	 */
	public function replaceProxies(ConfigurationDefinition $configurationDefinition);
}
