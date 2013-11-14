<?php

namespace Koala\AOP\Pointcut\Parser\AST\Element;

use Koala\AOP\Pointcut\Parser\AST\ContainerElement;
use Koala\AOP\Pointcut\Parser\AST\TypeList;

class MethodAnnotatedPointcut extends ContainerElement {

	/**
	 * @return TypeList
	 */
	protected function acceptElements() {
		return new TypeList(array(
			PointcutType::class,
			AnnotationClassExpression::class
		));
	}
}
