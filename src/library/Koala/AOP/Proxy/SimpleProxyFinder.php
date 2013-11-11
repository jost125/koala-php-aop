<?php

namespace Koala\AOP\Proxy;

use Koala\AOP\Abstraction\Proxy;
use Koala\AOP\Abstraction\ProxyList;
use Koala\AOP\Aspect\AspectReflection;
use Koala\AOP\Pointcut\PointcutExpressionResolver;
use Koala\Collection\ArrayList;
use Koala\Collection\IMap;
use Koala\Collection\Map;
use Koala\DI\Definition\Configuration\ServiceDefinition;
use ReflectionClass;

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
			$advicesJoinpoints = new Map();
			foreach ($aspectDefinitions as $aspectDefinition) {
				$aspect = $this->aspectReflection->getAspect(new ReflectionClass($aspectDefinition->getClassName()));
				$advices = $aspect->getAdvices();
				foreach ($advices as $advice) {
					$pointcutExpression = $advice->getPointcut()->getPointcutExpression();
					$joinpoints = $this->pointcutExpressionResolver->findJoinpoints(new ReflectionClass($targetDefinition->getClassName()), $pointcutExpression);
					if (count($joinpoints)) {
						$advicesJoinpoints->put($advice, [$joinpoints, $aspectDefinition]);
					}
				}
			}
			$groupedJoinpoints = $this->groupByJoinpoints($advicesJoinpoints);
			if (count($groupedJoinpoints)) {
				$proxyList->addProxy(new Proxy($groupedJoinpoints, $targetDefinition));
			}
		}

		return $proxyList;
	}

	private function groupByJoinpoints(IMap $advicesJoinpoints) {
		$groupedByJoinpoints = new Map();
		foreach ($advicesJoinpoints->getKeys() as $advice) {
			list($joinpoints, $aspectDefinition) = $advicesJoinpoints->getValue($advice);
			foreach ($joinpoints as $joinpoint) {
				if (!$groupedByJoinpoints->exists($joinpoint)) {
					$groupedByJoinpoints->put($joinpoint, new ArrayList());
				}
				/** @var ArrayList $advices */
				$advices = $groupedByJoinpoints->getValue($joinpoint);
				$advices->put([$advice, $aspectDefinition]);
			}
		}

		return $groupedByJoinpoints;
	}

}
