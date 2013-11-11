<?php

namespace AOP\Pointcut\Parser\AST\Element;

use AOP\Pointcut\Parser\AST\ContainerElement;
use AOP\Pointcut\Parser\AST\TypeList;

class Pointcut extends ContainerElement {

	/**
	 * @return TypeList
	 */
	protected function acceptElements() {
		return new TypeList(array(
			'AOP\Pointcut\Parser\AST\Element\PointcutType',
			'AOP\Pointcut\Parser\AST\Element\Modifier',
			'AOP\Pointcut\Parser\AST\Element\ClassExpression',
			'AOP\Pointcut\Parser\AST\Element\MethodExpression',
			'AOP\Pointcut\Parser\AST\Element\ArgumentsExpression',
			'AOP\Pointcut\Parser\AST\Element\NoArguments',
		));
	}
}
