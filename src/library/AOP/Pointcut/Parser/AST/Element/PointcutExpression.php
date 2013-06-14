<?php

namespace AOP\Pointcut\Parser\AST\Element;

use AOP\Pointcut\Parser\AST\ContainerElement;
use AOP\Pointcut\Parser\AST\TypeList;

class PointcutExpression extends ContainerElement {

	/**
	 * @return TypeList
	 */
	protected function acceptElements() {
		return new TypeList(array(
			'AOP\Pointcut\Parser\AST\Element\Pointcut',
			'AOP\Pointcut\Parser\AST\Element\PointcutOperator',
			'AOP\Pointcut\Parser\AST\Element\PointcutExpression',
			'AOP\Pointcut\Parser\AST\Element\PointcutExpressionGroup',
		));
	}

	public function generateCode() {
		// TODO: Implement generateCode() method.
	}
}
