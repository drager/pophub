<?php

require __DIR__.'/bootstrap/start.php';

use PopHub\Controller;

$router = new Controller\Router;

$router->doRoute();