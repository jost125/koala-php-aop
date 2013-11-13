<?php

namespace AOP\Proxy;

use FooAspect;
use FooService;
use Koala\AOP\Abstraction\Advice;
use Koala\AOP\Abstraction\InterceptingMethod;
use Koala\AOP\Abstraction\Joinpoint;
use Koala\AOP\Abstraction\Pointcut\BeforePointcut;
use Koala\AOP\Abstraction\Proxy;
use Koala\AOP\Abstraction\ProxyList;
use Koala\AOP\Interceptor\HashMapLoader;
use Koala\AOP\Pointcut\PointcutExpression;
use Koala\AOP\Proxy\Compiling\ProxyCompiler;
use Koala\AOP\Proxy\SimpleProxyGenerator;
use Koala\AOP\TestCase;
use Koala\Collection\ArrayList;
use Koala\Collection\Map;
use Koala\DI\Definition\Configuration\ArrayServiceDefinition;
use Koala\IO\Storage\FileStorage;
use PHPUnit_Framework_Comparator_MockObject;
use ReflectionClass;
use ReflectionMethod;

require_once __DIR__ . '/../../../fixtures/FooAspect.php';
require_once __DIR__ . '/../../../fixtures/FooService.php';

class SimpleProxyGeneratorTest extends TestCase {

	/** @var SimpleProxyGenerator */
	private $simpleProxyGenerator;

	/** @var PHPUnit_Framework_Comparator_MockObject */
	private $fileStorageMock;

	protected function setUp() {
		$this->fileStorageMock = $this->createMock(FileStorage::class);
		$this->simpleProxyGenerator = new SimpleProxyGenerator(
			new ProxyCompiler('___aop___', 'GeneratedAOPProxy'),
			'___aop___',
			'generatedInterceptorLoader',
			$this->fileStorageMock,
			'foo',
			'container'
		);
	}

	public function testGenerateProxies() {
		$proxies = $this->simpleProxyGenerator->generateProxies($this->getProxyListFixtures());
		$this->assertEquals($this->getExpected(), $proxies);
	}

	private function getProxyListFixtures() {
		$serviceReflectionClass = new ReflectionClass(FooService::class);
		$aspectReflectionClass = new ReflectionClass(FooAspect::class);

		$proxyList = new ProxyList();

		$joinpoint = new Joinpoint($serviceReflectionClass->getMethod('foo'));

		$advice = new Advice(
			new BeforePointcut(new PointcutExpression('\AOP\Before')),
			new InterceptingMethod($aspectReflectionClass->getMethod('fooAdvice'))
		);

		$aspectDefinition = new ArrayServiceDefinition(array(
			'serviceId' => 'fooService',
			'class' => FooAspect::class,
		));

		$joinpointsAdvices = new Map();
		$joinpointsAdvices->put($joinpoint, new ArrayList());
		$joinpointsAdvices->getValue($joinpoint)->put([$advice, $aspectDefinition]);

		$proxyList->addProxy(new Proxy(
			$joinpointsAdvices,
			new ArrayServiceDefinition(array(
				'serviceId' => 'fooService',
				'class' => '\FooService',
			))
		));

		return $proxyList;
	}

	private function getExpected() {
		return array(
			new ArrayServiceDefinition(array(
				'serviceId' => 'fooService',
				'class' => 'GeneratedAOPProxy\FooService',
				'arguments' => array(),
				'setup' => array(
					'set___aop___interceptorLoader' => array(
						array('service' => 'generatedInterceptorLoader')
					),
				),
			)),
			new ArrayServiceDefinition(array(
				'serviceId' => 'generatedInterceptorLoader',
				'class' => HashMapLoader::class,
				'arguments' => [
					['service' => 'container'],
					[
						'value' => [
							'FooService::foo' => [
								'before' => [
									['fooService', new ReflectionMethod(FooAspect::class, 'fooAdvice')]
								]
							]
						]
					],
				],
			)),
		);
	}

}
