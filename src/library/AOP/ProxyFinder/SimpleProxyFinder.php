<?php

namespace AOP\ProxyFinder;

use AOP\Abstraction\Proxy;
use AOP\Abstraction\ProxyList;
use AOP\Abstraction\ProxyListFactory;
use AOP\AspectReflection;
use AOP\PointcutExpressionResolver;
use AOP\ProxyFinder;
use DI\Definition\ServiceDefinition;
use ReflectionClass;

class SimpleProxyFinder implements ProxyFinder {

	/** @var AspectReflection */
	private $aspectReflection;

	/** @var PointcutExpressionResolver */
	private $pointcutExpressionResolver;

	/** @var ProxyListFactory */
	private $proxyListFactory;

	public function __construct(
		AspectReflection $aspectReflection,
		PointcutExpressionResolver $pointcutExpressionResolver,
		ProxyListFactory $proxyListFactory
	) {
		$this->aspectReflection = $aspectReflection;
		$this->pointcutExpressionResolver = $pointcutExpressionResolver;
		$this->proxyListFactory = $proxyListFactory;
	}

	/**
	 * @param ServiceDefinition[] $aspectDefinitions
	 * @param ServiceDefinition[] $targetDefinitions
	 * @return ProxyList
	 */
	public function findProxies(array $aspectDefinitions, array $targetDefinitions) {
		$proxyList = $this->proxyListFactory->createProxyList();

		foreach ($aspectDefinitions as $aspectDefinition) {
			$aspect = $this->aspectReflection->getAspect(new ReflectionClass($aspectDefinition->getClassName()));
			$advices = $aspect->getAdvices();
			foreach ($advices as $advice) {
				$pointcutExpression = $advice->getPointcut()->getPointcutExpression();
				foreach ($targetDefinitions as $targetDefinition) {
					$joinpoints = $this->pointcutExpressionResolver->findJoinpoints(new ReflectionClass($targetDefinition->getClassName()), $pointcutExpression);
					$proxyList->addProxy(new Proxy($advice, $joinpoints, $targetDefinition));
				}
			}
		}

		return $proxyList;
	}

}
