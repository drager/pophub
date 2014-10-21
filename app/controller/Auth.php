<?php

namespace PopHub\Controller;

use Kagu\Config\Config;

use PopHub\Model;
use PopHub\Model\Session;
use PopHub\View;

class Auth {
  /**
   * @var Userview
   */
  private $view;

  /**
   * @var Usermodel
   */
  private $model;

  /**
   * @return void
   */
  function __construct() {
    $config = new Config(__DIR__."/../config/app.php");

    $this->model = new Model\GithubAdapter(new Model\Github($config));
    $this->followers = new Model\Followers($config);

    $this->view = new View\Auth();
  }

  /**
   * The index action, tries to authorize an user.
   * @return String
   */
  public function index() {
    return $this->model->authorize();
  }

  /**
   * Get's a token via the given $code and sets a session
   * if the $code is valid.
   * @param String $code
   * @return String
   */
  public function getToken($code) {
    try {
      $token = $this->model->postAccessToken($code);
    } catch (\Exception $e) {
      return;
    }

    Session::set("access_token", $token);

    return $token;
  }

  /**
   * Logged in action, get's the session, current user,
   * those the user follow and activity
   * @param String $accessToken
   * @return View showLoggedIn
   */
  public function loggedInUser($accessToken) {
    $user = $this->model->getLoggedInUser($accessToken);

    // If we can't find the user, just destroy it.
    if ($user === null) {
      Session::destroy("access_token");
      return;
    }

    $auth = Session::get("access_token");

    $followers = $this->followers->getFollowers($user->getLogin());

    $events = null;

    if ($followers !== null) {
      foreach ($followers as $follower) {
        $events[] = $this->model->getUserActivity($follower["user"]);
      }
    }

    $context = array(
      "followers" => $followers,
      "user" => $user,
      "events" => $events,
      "authenticated" => $auth
    );

    return $this->view->showLoggedIn($context);
  }
}