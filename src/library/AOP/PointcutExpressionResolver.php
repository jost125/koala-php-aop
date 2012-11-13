<?php

namespace AOP;

interface PointcutExpressionResolver {
	/**
	 * @param string $className
	 * @param Pointcut\PointcutExpression $pointcutExpression
	 * @return \AOP\Abstraction\Joinpoint[]
	 */
	public function findJoinpoints($className, \AOP\Pointcut\PointcutExpression $pointcutExpression);
}
