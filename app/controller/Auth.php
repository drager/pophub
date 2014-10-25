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

  private $token = "access_token";

  /**
   * @return void
   */
  function __construct() {
    $config = new Config(__DIR__."/../config/app.php");

    $this->model = new Model\GithubAdapter(new Model\Github($config));
    $this->followers = new Model\Followers($config);

    $this->view = new View\Auth();
    $this->search = new View\Users();
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

    $session = new Session();

    $session->set($this->token, $token);

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

    $session = new Session();

    // If we can't find the user, just destroy it.
    if ($user === null) {
      $session->destroy($this->token);
      return;
    }

    $auth = $session->get($this->token);

    $followers = $this->followers->getFollowers($user->getLogin());

    $events = null;

    // Get events
    if ($followers !== null) {
      foreach ($followers as $follower) {
        $events[] = $this->model->getUserActivity($follower["user"]);
      }
    }

    $context = array(
      $this->view->getFollowersField() => $followers,
      $this->view->getUserField() => $user,
      $this->view->getEventField() => $events,
      $this->view->getAuthField() => $auth,
      $this->view->getSearchField() => $this->search->getSearchFieldName()
    );

    return $this->view->showLoggedIn($context);
  }
}