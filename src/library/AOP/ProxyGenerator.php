<?php

namespace AOP;

use AOP\Abstraction\ProxyList;
use DI\Definition\ServiceDefinition;

interface ProxyGenerator {
	/**
	 * @param ProxyList $proxyAbstractionList
	 * @return ServiceDefinition[]
	 */
	public function generateProxies(ProxyList $proxyAbstractionList);


}
