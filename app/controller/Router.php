<?php

namespace PopHub\Controller;

require_once '../vendor/autoload.php';

use Klein\Klein;

use Kagu\Config\Config;

use PopHub\Controller;
use PopHub\Model\Session;

class Router {
  private $config;

  public function doRoute() {
    $klein = new Klein();
    $this->config = new Config("../app/config/app.php");

    $klein->respond("GET", "/", function () {
      $controller = new Home();
      $controller->index();
    });

    $klein->respond("GET", "/login/?", function ($request, $response) {
      $controller = new Auth();
      $url = $controller->index();
      $response->redirect($url);
    });

    $klein->respond("GET", "/github-callback/?", function ($request, $response) {
      if ($request->code) {
        $controller = new Auth();
        $controller->getToken($request->code);
        return $response->redirect("/user/");
      }
      return $response->redirect("/");
    });

    $klein->respond("GET", "/user/?", function ($request, $response) {
      $controller = new Auth();
      $token = Session::get("access_token");

      if ($token) {
        return $controller->loggedInUser($token);
      }

      return $response->redirect("/");
    });

    $klein->respond("GET", "/logout/?", function ($request, $response) {
      Session::destroy("access_token");
      return $response->redirect("/");
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