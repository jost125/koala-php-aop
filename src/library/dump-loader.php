<?php

include_once __DIR__ . '/Koala/Tools/AutoLoaderCreator.php';
include_once __DIR__ . '/Koala/Collection/IList.php';
include_once __DIR__ . '/Koala/Collection/ArrayList.php';

use Koala\Tools\AutoLoaderCreator;

$creator = new AutoLoaderCreator();
$creator->create(__DIR__ . '/Koala', __DIR__ . '/classloader.php');
