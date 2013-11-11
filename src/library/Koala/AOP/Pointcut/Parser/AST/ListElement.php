<?php

namespace AOP\Pointcut\Parser\AST;

use ReflectionClass;

abstract class ListElement extends Element {

	private $value;

	public function __construct($value) {
		$this->value = $value;
	}

	public function acceptVisitor(ElementVisitor $visitor) {
		$reflectionClass = new ReflectionClass($this);
		$method = 'accept' . ucfirst($reflectionClass->getShortName());
		$visitor->{$method}($this);
	}

	public function getValue() {
		return $this->value;
	}
}
