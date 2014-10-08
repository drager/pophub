<?php

// Composer autoload
require __DIR__.'/../vendor/autoload.php';

// Own autoload
require_once __DIR__.'/../kagu/src/ClassLoader/Psr4AutoloaderClass.php';

$loader = new \Kagu\ClassLoader\Psr4AutoloaderClass;

// Register the autoloader
$loader->register();

// Register the base directories for the namespace prefix
$loader->addNamespace('PopHub\Controller', __DIR__."/controller/");
$loader->addNamespace('PopHub\Model', __DIR__."/model/");
$loader->addNamespace('PopHub\View', __DIR__."/view/");

$app = require __DIR__.'/bootstrap/start.php';

$app->doRoute();