<?php

namespace Koala\AOP\Pointcut;

use Koala\AOP\Abstraction\Joinpoint;
use Koala\AOP\Pointcut\Compiler\MethodMatcherCompiler;
use Koala\Reflection\MethodMatcher;
use ReflectionClass;
use ReflectionMethod;

class SimplePointcutExpressionResolver implements PointcutExpressionResolver {

	private $methodMatchers;
	private $methodMatcherCompiler;

	public function __construct(
		MethodMatcherCompiler $methodMatcherCompiler
	) {
		$this->methodMatchers = [];
		$this->methodMatcherCompiler = $methodMatcherCompiler;
	}

	/**
	 * @param ReflectionClass $reflectionClass
	 * @param PointcutExpression $pointcutExpression
	 * @return Joinpoint[]
	 */
	public function findJoinpoints(ReflectionClass $reflectionClass, PointcutExpression $pointcutExpression) {
		if (!isset($this->methodMatchers[$pointcutExpression->getExpression()])) {
			$matcherFQNClassName = $this->methodMatcherCompiler->compileMethodMatcher($pointcutExpression);
			$this->methodMatchers[$pointcutExpression->getExpression()] = new $matcherFQNClassName();
		}

		/** @var MethodMatcher $matcher */
		$matcher = $this->methodMatchers[$pointcutExpression->getExpression()];

		$joinpoints = [];
		foreach ($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC | ReflectionMethod::IS_PROTECTED) as $reflectionMethod) {
			if ($reflectionMethod->getName() != '__construct' && $matcher->match($reflectionMethod)) {
				$joinpoints[] = new Joinpoint($reflectionMethod);
			}
		}

		return $joinpoints;
	}
}
