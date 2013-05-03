<?php

namespace AOP\ProxyFinder;

use AOP\Abstraction\Proxy;
use AOP\Abstraction\ProxyList;
use AOP\AspectReflection;
use AOP\PointcutExpressionResolver;
use AOP\ProxyFinder;
use DI\Definition\ServiceDefinition;
use ReflectionClass;
use SplObjectStorage;

class SimpleProxyFinder implements ProxyFinder {

	/** @var AspectReflection */
	private $aspectReflection;

	/** @var PointcutExpressionResolver */
	private $pointcutExpressionResolver;

	public function __construct(
		AspectReflection $aspectReflection,
		PointcutExpressionResolver $pointcutExpressionResolver
	) {
		$this->aspectReflection = $aspectReflection;
		$this->pointcutExpressionResolver = $pointcutExpressionResolver;
	}

	/**
	 * @param ServiceDefinition[] $aspectDefinitions
	 * @param ServiceDefinition[] $targetDefinitions
	 * @return ProxyList
	 */
	public function findProxies(array $aspectDefinitions, array $targetDefinitions) {
		$proxyList = new ProxyList();

		foreach ($targetDefinitions as $targetDefinition) {
			$advicesJoinpoints = new SplObjectStorage();
			foreach ($aspectDefinitions as $aspectDefinition) {
				$aspect = $this->aspectReflection->getAspect(new ReflectionClass($aspectDefinition->getClassName()));
				$advices = $aspect->getAdvices();
				foreach ($advices as $advice) {
					$pointcutExpression = $advice->getPointcut()->getPointcutExpression();
					$joinpoints = $this->pointcutExpressionResolver->findJoinpoints(new ReflectionClass($targetDefinition->getClassName()), $pointcutExpression);
					$advicesJoinpoints->offsetSet($advice, $joinpoints);
				}
			}
			$proxyList->addProxy(new Proxy($this->groupByJoinpoints($advicesJoinpoints), $targetDefinition));
		}

		return $proxyList;
	}

	private function groupByJoinpoints(SplObjectStorage $advicesJoinpoints) {
		$groupedByJoinpoints = new SplObjectStorage();
		foreach ($advicesJoinpoints as $advice) {
			$joinpoints = $advicesJoinpoints->offsetGet($advice);
			foreach ($joinpoints as $joinpoint) {
				if (!$groupedByJoinpoints->contains($joinpoint)) {
					$groupedByJoinpoints->offsetSet($joinpoint, new SplObjectStorage());
				}
				$groupedByJoinpoints->offsetGet($joinpoint)->attach($advice);
			}
		}

		return $groupedByJoinpoints;
	}

}
