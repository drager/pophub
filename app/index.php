<?php

require __DIR__.'/bootstrap/start.php';

use PopHub\Controller;

session_start();

$router = new Controller\Router;

$router->doRoute();