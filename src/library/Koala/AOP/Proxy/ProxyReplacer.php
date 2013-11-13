<?php

namespace Koala\AOP\Proxy;

use Koala\DI\Definition\Configuration\ConfigurationDefinition;

interface ProxyReplacer {
	/**
	 * @param ConfigurationDefinition $configurationDefinition
	 * @return ConfigurationDefinition
	 */
	public function replaceProxies(ConfigurationDefinition $configurationDefinition);
}
