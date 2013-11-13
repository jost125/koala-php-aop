<?php

namespace Koala\AOP\Proxy;

use Koala\AOP\Abstraction\ProxyList;
use Koala\DI\Definition\Configuration\ServiceDefinition;

interface ProxyGenerator {
	/**
	 * @param ProxyList $proxyAbstractionList
	 * @return ServiceDefinition[]
	 */
	public function generateProxies(ProxyList $proxyAbstractionList);

}
