<?php

namespace Koala\AOP\Proxy\Compiling;

use Koala\AOP\TestCase;
use ReflectionClass;
use ReflectionMethod;

require_once __DIR__ . '/../../../../fixtures/Namespaced/BarService.php';
require_once __DIR__ . '/../../../../fixtures/FooService.php';
require_once __DIR__ . '/../../../../fixtures/BarInterface.php';
require_once __DIR__ . '/../../../../fixtures/BarService.php';

class ProxyCompilerTest extends TestCase {

	/** @var ProxyCompiler */
	private $proxyCompiler;

	protected function setUp() {
		$this->proxyCompiler = new ProxyCompiler('___aop___', 'GeneratedAOPProxy');
	}

	/**
	 * @dataProvider compilationFixtures
	 */
	public function testCompile($targetClass, $interceptedMethods, $expected) {
		$compiled = $this->proxyCompiler->compileProxy($targetClass, $interceptedMethods);
		$this->assertEquals($expected, $compiled);
	}

	public function compilationFixtures() {
		return array(
			array(
				new ReflectionClass('BarService'),
				array(new ReflectionMethod('BarService', 'bar'), new ReflectionMethod('BarService', 'foo')),
				new CompiledProxy('GeneratedAOPProxy\BarService', file_get_contents(__DIR__ . '/compiled.1')),
			),
			array(
				new ReflectionClass('BarService'),
				array(new ReflectionMethod('BarService', 'foo')),
				new CompiledProxy('GeneratedAOPProxy\BarService', file_get_contents(__DIR__ . '/compiled.2')),
			),
			array(
				new ReflectionClass('Namespaced\BarService'),
				array(new ReflectionMethod('Namespaced\BarService', 'foobar')),
				new CompiledProxy('GeneratedAOPProxy\Namespaced\BarService', file_get_contents(__DIR__ . '/compiled.3')),
			),
		);
	}

}
