<?php

include_once __DIR__ . '/AutoLoad/AutoLoaderDumper.php';
include_once __DIR__ . '/Collection/IList.php';
include_once __DIR__ . '/Collection/ArrayList.php';

use Koala\AutoLoad\AutoLoaderDumper;

$creator = new AutoLoaderDumper();
$creator->create(__DIR__, __DIR__ . '/classloader.php');
