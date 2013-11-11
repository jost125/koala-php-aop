<?php

namespace Koala\AOP\Proxy;

use FooAspect;
use FooService;
use Koala\AOP\Abstraction\Advice;
use Koala\AOP\Abstraction\Aspect;
use Koala\AOP\Abstraction\InterceptingMethod;
use Koala\AOP\Abstraction\Joinpoint;
use Koala\AOP\Abstraction\Pointcut;
use Koala\AOP\Abstraction\Proxy;
use Koala\AOP\Abstraction\ProxyList;
use Koala\AOP\Aspect\AspectReflection;
use Koala\AOP\Pointcut\PointcutExpression;
use Koala\AOP\Pointcut\PointcutExpressionResolver;
use Koala\AOP\TestCase;
use Koala\Collection\ArrayList;
use Koala\Collection\Map;
use Koala\DI\Definition\Configuration\ArrayServiceDefinition;
use Koala\DI\Definition\Configuration\ServiceDefinition;
use ReflectionClass;
use ReflectionMethod;
use SplObjectStorage;

require_once __DIR__ . '/../../../fixtures/FooAspect.php';
require_once __DIR__ . '/../../../fixtures/FooService.php';

class SimpleProxyFinderTest extends TestCase {

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	private $aspectReflectionMock;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	private $pointcutExpressionResolverMock;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	private $proxyListFactory;

	/** @var SimpleProxyFinder */
	private $simpleProxyFinder;

	protected function setUp() {
		$this->aspectReflectionMock = $this->createMock(AspectReflection::class);
		$this->pointcutExpressionResolverMock = $this->createMock(PointcutExpressionResolver::class);
		$this->simpleProxyFinder = new SimpleProxyFinder(
			$this->aspectReflectionMock,
			$this->pointcutExpressionResolverMock,
			$this->proxyListFactory
		);
	}

	public function testFindProxies() {
		$aspectDefinitionsFixtures = $this->getAspectDefinitionsFixtures();
		$aspectFixtures = $this->getAspectFixtures();

		$this->aspectReflectionMock->expects($this->once())
			->method('getAspect')
			->with(new ReflectionClass($aspectDefinitionsFixtures['fooAspect']->getClassName()))
			->will($this->returnValue($aspectFixtures));

		$this->pointcutExpressionResolverMock->expects($this->once())
			->method('findJoinpoints')
			->with(new ReflectionClass('FooService'), $this->getPointcutExpressionFixtures())
			->will($this->returnValue($this->getJoinpointsFixtures()));

		$proxyList = $this->simpleProxyFinder->findProxies($aspectDefinitionsFixtures, $this->getTargetDefinitionsFixtures());
		$it = $proxyList->getIterator();
		foreach ($this->getExpectedProxyList() as $expectedProxy) {
			/** @var Proxy $proxy */
			$proxy = $it->current();

			/** @var Proxy $expectedProxy */
			$this->assertEquals($expectedProxy->getTargetDefinition(), $proxy->getTargetDefinition());

			$joinpointsAdvices = $proxy->getJoinpointsAdvices();
			$joinpoints = $joinpointsAdvices->getKeys();
			$it2 = $joinpoints->getIterator();
			$expectedJoinpointAdvices = $expectedProxy->getJoinpointsAdvices();
			foreach ($expectedJoinpointAdvices->getKeys() as $expectedJoinpoint) {
				$this->assertEquals($expectedJoinpointAdvices->getValue($expectedJoinpoint), $joinpointsAdvices->getValue($it2->current()));
				$it2->next();
			}
			$it->next();
		}
	}
	public function getJoinpointsFixtures() {
		$reflectionClass = new ReflectionClass(FooAspect::class);
		$reflectionMethod = $reflectionClass->getMethod('fooAdvice');
		return array(
			new Joinpoint($reflectionMethod)
		);
	}

	/**
	 * @return ProxyList
	 */
	private function getExpectedProxyList() {
		$reflectionClass = new ReflectionClass(FooAspect::class);
		$reflectionMethod = $reflectionClass->getMethod('fooAdvice');
		$joinpoint = new Joinpoint($reflectionMethod);

		$advice = new Advice(
			new Pointcut($this->getPointcutExpressionFixtures()),
			new InterceptingMethod(new ReflectionMethod(FooAspect::class, 'fooAdvice'))
		);

		$adviceDefinition = new ArrayServiceDefinition(array(
			'serviceId' => 'fooAspect',
			'class' => FooAspect::class,
		));

		$joinpointsAdvices = new Map();
		$joinpointsAdvices->put($joinpoint, new ArrayList());
		$joinpointsAdvices->getValue($joinpoint)->put([$advice, $adviceDefinition]);

		$targetDefinition = new ArrayServiceDefinition(array(
			'serviceId' => 'fooService',
			'class' => FooService::class,
		));

		$proxyList = new ProxyList();
		$proxyList->addProxy(new Proxy($joinpointsAdvices, $targetDefinition));
		return $proxyList;
	}

	/**
	 * @return ServiceDefinition[]
	 */
	private function getAspectDefinitionsFixtures() {
		return array(
			'fooAspect' => new ArrayServiceDefinition(array(
				'serviceId' => 'fooAspect',
				'class' => FooAspect::class,
			))
		);
	}

	private function getTargetDefinitionsFixtures() {
		return array(
			'fooService' => new ArrayServiceDefinition(array(
				'serviceId' => 'fooService',
				'class' => FooService::class,
			))
		);
	}

	private function getAspectFixtures() {
		return new Aspect(array(
			new Advice(
				new Pointcut($this->getPointcutExpressionFixtures()),
				new InterceptingMethod(new ReflectionMethod(FooAspect::class, 'fooAdvice'))
			)
		));
	}

	private function getPointcutExpressionFixtures() {
		return new PointcutExpression('\AOP\Around("execution(public \SimpleProxyFinderTest\FooService::*(..))")');
	}

}
