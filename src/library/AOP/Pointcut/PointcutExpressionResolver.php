<?php

namespace AOP\Pointcut;

use AOP\Abstraction\Joinpoint;
use AOP\Pointcut\PointcutExpression;
use ReflectionClass;

interface PointcutExpressionResolver {
	/**
	 * @param ReflectionClass $reflectionClass
	 * @param PointcutExpression $pointcutExpression
	 * @return Joinpoint[]
	 */
	public function findJoinpoints(ReflectionClass $reflectionClass, PointcutExpression $pointcutExpression);
}
