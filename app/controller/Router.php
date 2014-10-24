<?php

namespace PopHub\Controller;

require_once '../vendor/autoload.php';

use Klein\Klein;

use Kagu\Config\Config;

use PopHub\Controller;
use PopHub\View;
use PopHub\Model\Session;

class Router {
  private $config;

  private $homePath = "/";

  private $userPath = "/user/";

  private $usersPath = "/users";

  private $loginPath = "/login/";

  private $logoutPath = "/logout/";

  private $searchPath = "/search/";

  private $followPath = "/follow/";

  private $unFollowPath = "/unfollow/";

  public function doRoute() {
    $klein = new Klein();
    $this->config = new Config("../app/config/app.php");

    $klein->respond("GET", $this->homePath, function () {
      $controller = new Home(new View\Home());
      $controller->index();
    });

    $klein->respond("GET", $this->loginPath . "?", function ($request, $response) {
      $controller = new Auth();
      $url = $controller->index();
      $response->redirect($url);
    });

    $klein->respond("GET", "/github-callback/?", function ($request, $response) {
      if ($request->code) {
        $controller = new Auth();
        $controller->getToken($request->code);
        return $response->redirect($this->userPath);
      }
      return $response->redirect($this->homePath);
    });

    $klein->respond("GET", $this->userPath . "?", function ($request, $response) {
      $controller = new Auth();
      $session = new Session();
      $token = $session->get("access_token");

      if ($token) {
        return $controller->loggedInUser($token);
      }

      return $response->redirect($this->homePath);
    });

    $klein->respond("GET", $this->logoutPath . "?", function ($request, $response) {
      $session = new Session();
      $session->destroy("access_token");
      return $response->redirect($this->homePath);
    });

    $klein->with($this->usersPath, function () use ($klein) {

      $klein->respond("GET", "/?", function ($request, $response) {
        $controller = new Users();
        $controller->index($request->fetchFromID);
      });

      $klein->respond("GET", "/[:username]/?", function ($request, $response) {
        $controller = new Users();
        $controller->show($request->username);
      });

    });

    $klein->respond("GET", $this->searchPath . "?", function ($request, $response) {
      $controller = new Users();
      $controller->search();
    });

    $klein->respond("GET", $this->followPath . "[:username]/?", function ($request, $response) {
      $controller = new Users();
      $controller->follow($request->username);
    });

    $klein->respond("GET", $this->unFollowPath . "[:username]/?", function ($request, $response) {
      $controller = new Users();
      $controller->unFollow($request->username);
    });

    $klein->respond("404", function ($request) {
      $page = $request->uri();
      $controller = new Error(new View\Error());
      $controller->pageNotFoundError($page);
    });

    $klein->onError(function ($klein, $err_msg) {
      if ($this->config->get("DEBUG") === true) {
        throw new \Exception($err_msg);
      }
      else {
        $controller = new Error(new View\Error());
        $controller->serverError();
        error_log($err_msg, 3, $this->config->get("ERROR_LOG"));
      }
    });

    $klein->dispatch();
  }
}