<?php
require 'bootstrap.php';

/** @var $articleController \Example\Controller\ArticleController */
$articleController = $diContainer->getService('articleController');
$articleController->actionArticle();

/** @var $articleController \Example\Controller\HiController */
$articleController = $diContainer->getService('hiController');
$articleController->sayHiAction();
