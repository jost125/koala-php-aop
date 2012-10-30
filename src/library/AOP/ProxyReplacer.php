<?php

namespace AOP;

interface ProxyReplacer {
	/**
	 * @param \DI\Definition\ConfigurationDefinition $configurationDefinition
	 * @return \DI\Definition\ConfigurationDefinition
	 */
	public function replaceProxies(\DI\Definition\ConfigurationDefinition $configurationDefinition);
}
