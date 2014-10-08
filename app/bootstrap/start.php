<?php

// Composer autoload
require __DIR__.'/../../vendor/autoload.php';

// Own autoload
require_once __DIR__.'/../../kagu/src/ClassLoader/Psr4AutoloaderClass.php';

$loader = new \Kagu\ClassLoader\Psr4AutoloaderClass;

// Register the autoloader
$loader->register();

$appPath = __DIR__."/../";
$kaguPath = __DIR__."/../../kagu/src/";

// Register the base directories for the namespace prefix
$loader->addNamespace('PopHub\Controller', $appPath."controller/");
$loader->addNamespace('PopHub\Model', $appPath."model/");
$loader->addNamespace('PopHub\View', $appPath."view/");

// Kagu things
$loader->addNamespace('Kagu\Http', $kaguPath."Http/");
$loader->addNamespace('Kagu\Config', $kaguPath."Config/");
$loader->addNamespace('Kagu\Exception', $kaguPath."Exception/");
