<?php

namespace Controller;

require_once '../vendor/autoload.php';
require_once '../kagu/src/Config/Config.php';
require_once 'controller/Users.php';
require_once 'controller/Home.php';
require_once 'controller/Error.php';
require_once 'controller/Login.php';

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing;
use Klein\Klein;

use Kagu\Config\Config;
use Controller;

class Routers {
  private $config;

  public function doRoute() {
    $klein = new Klein();
    $this->config = new Config("../app/config/app.php");

    $klein->respond("GET", "/", function () {
      $controller = new Home();
      $controller->index();
    });

    $klein->respond("GET", "/login/?", function ($request, $response) {
      $controller = new Login();
      $url = $controller->index();
      $response->redirect($url);
    });

    $klein->respond("GET", "/github-callback/?", function ($request, $response) {
      // $response->redirect("localhost:9999");
    });

    $klein->with("/users", function () use ($klein) {

      $klein->respond("GET", "/?", function ($request, $response) {
        $controller = new Users();
        $controller->index($request->fetchFromID);
      });

      $klein->respond("GET", "/[:username]/?", function ($request, $response) {
        $controller = new Users();
        $controller->show($request->username);
      });

    });

    $klein->respond("404", function ($request) {
      $page = $request->uri();
      $controller = new Error();
      $controller->pageNotFoundError($page);
    });

    $klein->onError(function ($klein, $err_msg) {
      if ($this->config->get("DEBUG") === true) {
        throw new \Exception($err_msg);
      }
      else {
        $controller = new Error();
        $controller->serverError();
        error_log($err_msg, 3, $this->config->get("ERROR_LOG")); 
      }
    });

    $klein->dispatch();
  }
}