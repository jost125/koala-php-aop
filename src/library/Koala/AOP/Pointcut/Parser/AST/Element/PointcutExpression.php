<?php

namespace Koala\AOP\Pointcut\Parser\AST\Element;

use Koala\AOP\Pointcut\Parser\AST\ContainerElement;
use Koala\AOP\Pointcut\Parser\AST\TypeList;

class PointcutExpression extends ContainerElement {

	/**
	 * @return TypeList
	 */
	protected function acceptElements() {
		return new TypeList(array(
			Pointcut::class,
			PointcutOperator::class,
			PointcutExpression::class,
			PointcutExpressionGroupStart::class,
			PointcutExpressionGroupEnd::class,
		));
	}

}
