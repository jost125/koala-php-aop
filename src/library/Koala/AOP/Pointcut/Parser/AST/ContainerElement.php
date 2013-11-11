<?php

namespace AOP\Pointcut\Parser\AST;

use ReflectionClass;

abstract class ContainerElement extends Element {

	/** @var Element[] */
	private $elements;

	public function addElement(Element $element) {
		if (!$this->acceptElements()->oneOfUs($element)) {
			throw new NotAcceptableElementException('Cannot accept element of type ' . get_class($element));
		}
		$this->elements[] = $element;

	}

	/**
	 * @return TypeList
	 */
	abstract protected function acceptElements();

	public function acceptVisitor(ElementVisitor $visitor) {
		foreach ($this->elements as $element) {
			$element->acceptVisitor($visitor);
		}
		$reflectionClass = new ReflectionClass($this);
		$method = 'accept' . ucfirst($reflectionClass->getShortName());
		$visitor->{$method}($this);
	}

}
