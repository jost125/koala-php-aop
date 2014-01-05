<?php

use Koala\AOP\DI\Extension\AOPExtension;
use Koala\AOP\Proxy\SimpleProxyReplacerFactory;
use Koala\AutoLoad\PSR0AutoLoader;
use Koala\Collection\ArrayList;
use Koala\DI\Container;

require_once __DIR__ . '/../../../vendor/autoload.php';

$autoload = new PSR0AutoLoader(new ArrayList([__DIR__ . '/library/']));
$autoload->register();

new \Example\Transactional([]); // hack to import
new \Example\JsonResponse([]); // hack to import
new \Example\FileTemplateResponse([]); // hack to import

$configurationDefinition = require __DIR__ . '/wiring.php';

$proxyMemberPrefix = '__aop__';
$proxyNamespacePrefix = '__AOP__';
$matcherNamespace = 'MethodMatcher';
$interceptorLoaderId = 'generatedInterceptorLoader';
$containerId = 'container';
$cacheDir = __DIR__ . '/tmp/cache/';

$proxyReplacerFactory = new SimpleProxyReplacerFactory($proxyMemberPrefix, $proxyNamespacePrefix, $matcherNamespace, $interceptorLoaderId, $containerId, $cacheDir);

$diContainer = new Container($configurationDefinition);
$diContainer->registerBeforeCompileExtension(new AOPExtension($proxyReplacerFactory->create()));
$diContainer->compile();
