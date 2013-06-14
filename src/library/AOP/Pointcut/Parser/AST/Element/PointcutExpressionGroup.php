<?php

namespace AOP\Pointcut\Parser\AST\Element;

use AOP\Pointcut\Parser\AST\ContainerElement;
use AOP\Pointcut\Parser\AST\TypeList;

class PointcutExpressionGroup extends ContainerElement {

	/**
	 * @return TypeList
	 */
	protected function acceptElements() {
		return new TypeList(array(
			'AOP\Pointcut\Parser\AST\Element\PointcutExpression',
		));
	}

	public function generateCode() {
		// TODO: Implement generateCode() method.
	}
}
