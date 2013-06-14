<?php

namespace AOP\Pointcut\Parser\AST;

abstract class ContainerElement extends Element {

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

}
