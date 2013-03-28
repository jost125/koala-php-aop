<?php

namespace AOP;

use DI\Definition\ConfigurationDefinition;

interface ProxyReplacer {
	/**
	 * @param ConfigurationDefinition $configurationDefinition
	 * @return ConfigurationDefinition
	 */
	public function replaceProxies(ConfigurationDefinition $configurationDefinition);
}
