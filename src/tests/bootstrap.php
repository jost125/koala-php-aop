<?php
require_once __DIR__ . '/TestCase.php';
require_once __DIR__ . '/../library/loader.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Doctrine\Common\Annotations\AnnotationRegistry;

new \Koala\AOP\Aspect([]); // Hack to import
new \Koala\AOP\Around([]); // Hack to import
new \Koala\AOP\After([]); // Hack to import
new \Koala\AOP\Before([]); // Hack to import

AnnotationRegistry::registerAutoloadNamespace('\AOP', __DIR__ . '/../library');
