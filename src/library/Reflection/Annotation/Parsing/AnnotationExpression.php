<?php

namespace Reflection\Annotation\Parsing;

class AnnotationExpression {
	private $expression;

	function __construct($expression) {
		$this->expression = $expression;
	}

	public function getExpression() {
		return $this->expression;
	}
}
