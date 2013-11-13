<?php
require 'bootstrap.php';

/** @var $articleController \Example\Controller\HiController */
$articleController = $diContainer->getService('hiController');
$articleController->sayHiAction('John')->render();
