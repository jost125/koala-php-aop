<?php

namespace AOP\Pointcut\Parser\AST;

class TypeList {

	private $types;

	public function __construct(array $types) {
		$this->types = $types;
	}

	public function oneOfUs($object) {
		return in_array(get_class($object), $this->types);
	}

}
