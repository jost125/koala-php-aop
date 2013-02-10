<?php

namespace AOP;

use ReflectionClass;

interface PointcutExpressionResolver {
	/**
	 * @param ReflectionClass $reflectionClass
	 * @param Pointcut\PointcutExpression $pointcutExpression
	 * @return \AOP\Abstraction\Joinpoint[]
	 */
	public function findJoinpoints(ReflectionClass $reflectionClass, \AOP\Pointcut\PointcutExpression $pointcutExpression);
}
