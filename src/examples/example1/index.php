<?php
require 'bootstrap.php';

/** @var $articleController \Example\Controller\ArticleController */
$articleController = $diContainer->getService('articleController');
$articleController->actionArticle();
