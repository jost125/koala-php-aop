<?php

use Doctrine\Common\Annotations\AnnotationReader;
use Koala\AOP\Advice\SimpleAdviceReflection;
use Koala\AOP\Aspect\PhpNativeAspectServiceFilter;
use Koala\AOP\Aspect\SimpleAspectReflection;
use Koala\AOP\Pointcut\Compiler\MethodMatcherCompiler;
use Koala\AOP\Pointcut\Compiler\PointcutToMatcherClassTranslation;
use Koala\AOP\Pointcut\SimplePointcutExpressionResolver;
use Koala\AOP\Proxy\Compiling\ProxyCompiler;
use Koala\AOP\Proxy\SimpleProxyBuilder;
use Koala\AOP\Proxy\SimpleProxyFinder;
use Koala\AOP\Proxy\SimpleProxyGenerator;
use Koala\AOP\Proxy\SimpleProxyReplacer;
use Koala\Cache\FileCache;
use Koala\DI\Container;
use Koala\DI\Definition\Configuration\ArrayConfigurationDefinition;
use Koala\IO\Storage\FileStorage;
use Koala\Reflection\Annotation\Parsing\DoctrineAnnotationResolver;
use Koala\Reflection\Annotation\Parsing\SimpleAnnotationExpressionMatcher;

spl_autoload_register(function($className) {
	if (preg_match('~^[\\a-zA-Z0-9]+$~', $className)) {
		$fileName = preg_replace('~\\\\~', '/', $className) . '.php';
		$filePath = __DIR__ . '/library/' . $fileName;
		if (file_exists($filePath)) {
			require_once $filePath;
		}
	}
});

require_once __DIR__ . '/../../library/loader.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

$configurationDefinition = new ArrayConfigurationDefinition(
	array(
		'params' => array(
			'my.param' => 'hi',
		),
		'services' => array(
			'hiController' => array(
				'serviceId' => 'hiController',
				'class' => '\Example\Controller\HiController',
				'arguments' => array(),
				'setup' => array(
					'setHiMessage' => array(
						array('param' => 'my.param')
					)
				),
			),
			'articleController' => array(
				'serviceId' => 'articleController',
				'class' => '\Example\Controller\ArticleController',
				'arguments' => array(
					array('param' => 'my.param'),
					array('service' => 'articleModelFacade'),
				),
			),
			'articleModelFacade' => array(
				'serviceId' => 'articleModelFacade',
				'class' => '\Example\Model\Facade\ArticleModelFacade',
				'arguments' => array(),
			),
			'logger' => array(
				'serviceId' => 'logger',
				'class' => '\Example\Logger\StdLogger',
				'arguments' => array(),
			),
			'executionLogging' => [
				'serviceId' => 'executionLogging',
				'class' => '\Example\Aspect\ExecutionLogging',
				'arguments' => [
					['service' => 'logger'],
				],
			],
		)
	)
);

new \Koala\AOP\Aspect([]); // Hack to import
new \Koala\AOP\Around([]); // Hack to import

$proxyMemberPrefix = '__aop___';
$proxyNamespacePrefix = '__AOP__';
$interceptorLoaderId = 'generatedInterceptorLoader';
$doctrineAnnotationResolver = new DoctrineAnnotationResolver(new AnnotationReader(), new SimpleAnnotationExpressionMatcher());
$proxyCompiler = new ProxyCompiler($proxyMemberPrefix, $proxyNamespacePrefix);
$proxyGenerator = new SimpleProxyGenerator($proxyCompiler, $proxyMemberPrefix, $interceptorLoaderId, new FileStorage(__DIR__ . '/tmp/cache/proxy'), __DIR__ . '/tmp/cache/proxy', 'container');
$adviceReflection = new SimpleAdviceReflection($doctrineAnnotationResolver);
$aspectReflection = new SimpleAspectReflection($adviceReflection);
$methodMatcherFileStorage = new FileStorage(__DIR__ . '/tmp/cache/MethodMatcher');
$pointcutToMatcherClassTranslation = new PointcutToMatcherClassTranslation(new FileCache(__DIR__ . '/tmp/cache/pointcutToMatcherClass.cache'));
$methodMatcherCompiler = new MethodMatcherCompiler($methodMatcherFileStorage, $pointcutToMatcherClassTranslation, 'MethodMatcher', __DIR__ . '/tmp/cache/MethodMatcher');
$pointcutExpressionResolver = new SimplePointcutExpressionResolver($methodMatcherCompiler);
$proxyFinder = new SimpleProxyFinder($aspectReflection, $pointcutExpressionResolver);
$simpleProxyBuilder = new SimpleProxyBuilder($proxyGenerator, $proxyFinder);
$aspectReflectionResolver = new PhpNativeAspectServiceFilter($doctrineAnnotationResolver);
$proxyReplacer = new SimpleProxyReplacer($aspectReflectionResolver, $simpleProxyBuilder);

$diContainer = new Container($configurationDefinition, $proxyReplacer);
