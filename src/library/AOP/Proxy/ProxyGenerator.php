<?php

namespace AOP\Proxy;

use AOP\Abstraction\ProxyList;
use DI\Definition\Configuration\ServiceDefinition;

interface ProxyGenerator {
	/**
	 * @param ProxyList $proxyAbstractionList
	 * @return ServiceDefinition[]
	 */
	public function generateProxies(ProxyList $proxyAbstractionList);


}
