<?php

namespace PopHub\Controller;

use Kagu\Config\Config;

use PopHub\Model;
use PopHub\Model\Session;

class Auth extends BaseController {
  /**
   * @var Userview
   */
  private $view;

  /**
   * @var Usermodel
   */
  private $model;
  
  function __construct() {
    $config = new Config(__DIR__."/../config/app.php");
    $this->model = new Model\Github($config->get("GITHUB_CLIENT_ID"), $config->get("GITHUB_CLIENT_SECRET"), $config->get("GITHUB_CALLBACK_URL"));
  }

  public function index() {
    $auth = $this->model->authorize();

    var_dump($auth);

    return $auth;
  }

  public function getToken($code) {
    $token = $this->model->postAccessToken($code);

    Session::set("access_token", $token);

    var_dump($token);

    return $token;
  }

  public function loggedInUser($accessToken) {
    $user = $this->model->getLoggedInUser($accessToken);

    $auth = Session::get("access_token");

    echo $this->render('logged_in.html', array(
      "user" => $user,
      "authenticated" => $auth
    ));
  }
}