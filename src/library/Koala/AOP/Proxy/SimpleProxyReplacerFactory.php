<?php

namespace Koala\AOP\Proxy;

use Doctrine\Common\Annotations\AnnotationReader;
use Koala\AOP\Advice\SimpleAdviceReflection;
use Koala\AOP\Aspect\PhpNativeAspectServiceFilter;
use Koala\AOP\Aspect\SimpleAspectReflection;
use Koala\AOP\Pointcut\Compiler\MethodMatcherCompiler;
use Koala\AOP\Pointcut\Compiler\PointcutToMatcherClassTranslation;
use Koala\AOP\Pointcut\SimplePointcutExpressionResolver;
use Koala\AOP\Proxy\Compiling\ProxyCompiler;
use Koala\Cache\FileCache;
use Koala\IO\Storage\FileStorage;
use Koala\Reflection\Annotation\Parsing\DoctrineAnnotationResolver;
use Koala\Reflection\Annotation\Parsing\SimpleAnnotationExpressionMatcher;

class SimpleProxyReplacerFactory implements ProxyReplacerFactory {

	private $proxyMemberPrefix;
	private $proxyNamespacePrefix;
	private $matcherNamespace;
	private $interceptorLoaderId;
	private $containerId;
	private $cacheDir;

	public function __construct(
		$proxyMemberPrefix,
		$proxyNamespacePrefix,
		$matcherNamespace,
		$interceptorLoaderId,
		$containerId,
		$cacheDir
	) {
		$this->proxyMemberPrefix = $proxyMemberPrefix;
		$this->proxyNamespacePrefix = $proxyNamespacePrefix;
		$this->matcherNamespace = $matcherNamespace;
		$this->interceptorLoaderId = $interceptorLoaderId;
		$this->containerId = $containerId;
		$this->cacheDir = $cacheDir;
	}

	public function create() {
		$proxyDir = $this->cacheDir . '/proxy';
		$methodMatcherDir = $this->cacheDir . '/MethodMatcher';
		$pointcutToMatcherClassCacheDir = $this->cacheDir . '/pointcutToMatcherClass.cache';

		$doctrineAnnotationResolver = new DoctrineAnnotationResolver(new AnnotationReader(), new SimpleAnnotationExpressionMatcher());
		$proxyCompiler = new ProxyCompiler($this->proxyMemberPrefix, $this->proxyNamespacePrefix);
		$proxyGenerator = new SimpleProxyGenerator($proxyCompiler, $this->proxyMemberPrefix, $this->interceptorLoaderId, new FileStorage($proxyDir), $proxyDir, $this->containerId);
		$adviceReflection = new SimpleAdviceReflection($doctrineAnnotationResolver);
		$aspectReflection = new SimpleAspectReflection($adviceReflection);
		$methodMatcherFileStorage = new FileStorage($methodMatcherDir);
		$pointcutToMatcherClassTranslation = new PointcutToMatcherClassTranslation(new FileCache($pointcutToMatcherClassCacheDir));
		$methodMatcherCompiler = new MethodMatcherCompiler($methodMatcherFileStorage, $pointcutToMatcherClassTranslation, $this->matcherNamespace, $methodMatcherDir);
		$pointcutExpressionResolver = new SimplePointcutExpressionResolver($methodMatcherCompiler);
		$proxyFinder = new SimpleProxyFinder($aspectReflection, $pointcutExpressionResolver);
		$simpleProxyBuilder = new SimpleProxyBuilder($proxyGenerator, $proxyFinder);
		$aspectReflectionResolver = new PhpNativeAspectServiceFilter($doctrineAnnotationResolver);
		return new SimpleProxyReplacer($aspectReflectionResolver, $simpleProxyBuilder);
	}
}
