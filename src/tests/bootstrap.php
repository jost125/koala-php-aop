<?php
require_once __DIR__ . '/TestCase.php';
require_once __DIR__ . '/../library/loader.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Doctrine\Common\Annotations\AnnotationRegistry;

AnnotationRegistry::registerAutoloadNamespace('\AOP', __DIR__ . '/../library');
