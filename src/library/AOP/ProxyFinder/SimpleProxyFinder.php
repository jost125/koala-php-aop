<?php

namespace AOP\ProxyFinder;

use ReflectionClass;

class SimpleProxyFinder implements \AOP\ProxyFinder {

	/** @var \AOP\AspectReflection */
	private $aspectReflection;

	/** @var \AOP\PointcutExpressionResolver */
	private $pointcutExpressionResolver;

	/** @var \AOP\Abstraction\ProxyListFactory */
	private $proxyListFactory;

	public function __construct(
		\AOP\AspectReflection $aspectReflection,
		\AOP\PointcutExpressionResolver $pointcutExpressionResolver,
		\AOP\Abstraction\ProxyListFactory $proxyListFactory
	) {
		$this->aspectReflection = $aspectReflection;
		$this->pointcutExpressionResolver = $pointcutExpressionResolver;
		$this->proxyListFactory = $proxyListFactory;
	}

	/**
	 * @param \DI\Definition\ServiceDefinition[] $aspectDefinitions
	 * @param \DI\Definition\ServiceDefinition[] $targetDefinitions
	 * @return \AOP\Abstraction\ProxyList
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
					$proxyList->addProxy(new \AOP\Abstraction\Proxy($advice, $joinpoints, $targetDefinition));
				}
			}
		}

		return $proxyList;
	}

}
