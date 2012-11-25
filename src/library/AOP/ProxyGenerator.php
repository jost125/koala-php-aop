<?php

namespace AOP;

interface ProxyGenerator {
	/**
	 * @param Abstraction\ProxyList $proxyAbstractionList
	 * @return \DI\Definition\ServiceDefinition[]
	 */
	public function generateProxies(\AOP\Abstraction\ProxyList $proxyAbstractionList);


}
