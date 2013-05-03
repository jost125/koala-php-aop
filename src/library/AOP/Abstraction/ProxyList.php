<?php

namespace AOP\Abstraction;

use ArrayIterator;
use AOP\Abstraction\Proxy;

class ProxyList {

	/**
	 * @var Proxy[]
	 */
	private $proxies;

	public function __construct() {
		$this->proxies = array();
	}

	/**
	 * @param Proxy $proxy
	 * @return void
	 */
	public function addProxy(Proxy $proxy) {
		$this->proxies[] = $proxy;
	}

	public function getIterator() {
		return new ArrayIterator($this->proxies);
	}
}
