<?php

namespace Koala\AOP\Proxy\Compiling;

class CompiledProxy {

	private $code;
	private $className;

	public function __construct($className, $code) {
		$this->className = $className;
		$this->code = $code;
	}

	public function getClassName() {
		return $this->className;
	}

	public function getCode() {
		return $this->code;
	}

}
