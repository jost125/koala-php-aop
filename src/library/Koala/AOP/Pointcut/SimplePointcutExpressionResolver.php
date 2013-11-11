<?php

namespace AOP\Pointcut;

use AOP\Abstraction\Joinpoint;
use AOP\Pointcut\Compiler\MethodMatcherCompiler;
use AOP\Pointcut\PointcutExpression;
use Reflection\MethodMatcher;
use ReflectionClass;

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
		foreach ($reflectionClass->getMethods() as $reflectionMethod) {
			if ($matcher->match($reflectionMethod)) {
				$joinpoints[] = new Joinpoint($reflectionMethod);
			}
		}

		return $joinpoints;
	}
}
