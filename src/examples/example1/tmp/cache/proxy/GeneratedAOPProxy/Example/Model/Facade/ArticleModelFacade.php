<?php

namespace GeneratedAOPProxy\Example\Model\Facade;

use AOP\Interceptor\Loader;
use AOP\Interceptor\MethodInvocation;

class ArticleModelFacade extends \Example\Model\Facade\ArticleModelFacade {

	/** @var Loader */
	private $___aop___interceptorLoader;

	public function fetchArticleById($articleId) {
		$reflectionMethod = new \ReflectionMethod('\\Example\\Model\\Facade\\ArticleModelFacade', 'fetchArticleById');
		$interceptors = $this->___aop___interceptorLoader->loadInterceptors($reflectionMethod);
		$invocation = new MethodInvocation($this, $reflectionMethod, func_get_args(), $interceptors);
		return $invocation->proceed();
	}

	public function ___aop___setInterceptorLoader(Loader $loader) {
		$this->___aop___interceptorLoader = $loader;
	}
}
