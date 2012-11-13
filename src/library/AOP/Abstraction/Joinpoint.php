<?php

namespace AOP\Abstraction;

class Joinpoint {

	private $className;
	private $methodName;

	public function __construct($className, $methodName) {
		$this->className = $className;
		$this->methodName = $methodName;
	}

	public function getClassName() {
		return $this->className;
	}

	public function getMethodName() {
		return $this->methodName;
	}
}
