<?php

namespace Koala\AOP\Pointcut;

use Koala\AOP\Abstraction\Joinpoint;
use ReflectionClass;

interface PointcutExpressionResolver {
	/**
	 * @param ReflectionClass $reflectionClass
	 * @param PointcutExpression $pointcutExpression
	 * @return Joinpoint[]
	 */
	public function findJoinpoints(ReflectionClass $reflectionClass, PointcutExpression $pointcutExpression);
}
