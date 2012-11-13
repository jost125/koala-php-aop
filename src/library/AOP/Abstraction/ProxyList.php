<?php

namespace AOP\Abstraction;

interface ProxyList {
	/**
	 * @param Proxy $proxy
	 * @return void
	 */
	public function addProxy(Proxy $proxy);
}
