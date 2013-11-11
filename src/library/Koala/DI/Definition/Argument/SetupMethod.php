<?php

namespace Koala\DI\Definition\Argument;

class SetupMethod {

	private $methodName;
	private $arguments;

	/**
	 * @param string $methodName
	 * @param WiringArgument[] $arguments
	 */
	public function __construct($methodName, array $arguments) {
		$this->methodName = $methodName;
		$this->arguments = $arguments;
	}

	public function getArguments() {
		return $this->arguments;
	}

	public function getMethodName() {
		return $this->methodName;
	}

}
