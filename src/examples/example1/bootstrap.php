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
use Koala\AutoLoad\PSR0AutoLoader;
use Koala\Cache\FileCache;
use Koala\Collection\ArrayList;
use Koala\DI\Container;
use Koala\IO\Storage\FileStorage;
use Koala\Reflection\Annotation\Parsing\DoctrineAnnotationResolver;
use Koala\Reflection\Annotation\Parsing\SimpleAnnotationExpressionMatcher;

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../library/loader.php';

$autoload = new PSR0AutoLoader(new ArrayList([__DIR__ . '/library/']));
$autoload->register();

$configurationDefinition = require __DIR__ . '/wiring.php';

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
