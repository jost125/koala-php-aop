<?php

namespace AOP;

interface ProxyGenerator {
	/**
	 * @param Abstraction\ProxyList $proxyAbstraction
	 * @return \DI\Definition\ServiceDefinition[]
	 */
	public function generateProxies(\AOP\Abstraction\ProxyList $proxyAbstraction);


}
