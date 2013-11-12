<?php

include_once __DIR__ . '/Koala/AutoLoad/AutoLoaderDumper.php';
include_once __DIR__ . '/Koala/Collection/IList.php';
include_once __DIR__ . '/Koala/Collection/ArrayList.php';

use Koala\AutoLoad\AutoLoaderDumper;

$creator = new AutoLoaderDumper();
$creator->create(__DIR__ . '/Koala', __DIR__ . '/classloader.php');
