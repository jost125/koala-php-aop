<?php

namespace Koala\AOP\DI\Extension;

use Koala\AOP\Proxy\ProxyReplacer;
use Koala\DI\Definition\Configuration\ConfigurationDefinition;
use Koala\DI\Extension\IBeforeCompileExtension;

class AOPExtension implements IBeforeCompileExtension {

	private $proxyReplacer;

	public function __construct(ProxyReplacer $proxyReplacer) {
		$this->proxyReplacer = $proxyReplacer;
	}

	public function load(ConfigurationDefinition $configurationDefinition) {
		return $this->proxyReplacer->replaceProxies($configurationDefinition);
	}
}
