<?php

namespace PopHub\Controller;

use Kagu\Config\Config;
use Kagu\Exception\HttpStatus404Exception;

use PopHub\Model;
use PopHub\Model\Session;
use PopHub\View;

class Users {
  /**
   * @var Userview
   */
  private $view;

  /**
   * @var Usermodel
   */
  private $model;

  /**
   * Constructor
   */
  function __construct() {
    $config = new Config(__DIR__."/../config/app.php");
    $this->model = new Model\GithubAdapter(new Model\Github($config));
    $this->followers = new Model\Followers($config);
    $this->view = new View\Users();
    $this->errorView = new View\Error();
  }

  /**
   * Index action, listing all the users.
   * @return The show all users view
   */
  public function index() {
    $page = $this->view->getPage();

    $language = $this->view->getLanguage();

    try {
      if ($language !== null) {
        $users = $this->model->getAllUsers($page, "followers", $language);
      } else {
        if ($this->view->getSortBy() === "repos") {
          $sortBy = $this->view->getSortBy();
          $users = $this->model->getAllUsers($page, $sortBy);
        } else {
          $users = $this->model->getAllUsers($page, "followers");
        }
      }
    } catch (HttpStatus404Exception $e) {
      return $this->errorView->showPageNotFound("/users/?page=" . $page);
    }

    $pages = $users["pages"];

    // RESPONSE FEL FRÅN GITHUB?

    // ^GITHUB SKICKAR BARA UT 1000 RESULTAT…

   /** @note
    * Twig is smart enough to figure out which getters are available
    * so Twig can get $user->login instead of $user->getLogin.
    * Both will work though, but if we remove the getter getLogin()
    * Twig cannot access $user->login, so the private field is'nt explosed,
    * Twig is just smart enough to use those getters.
    */

    $session = new Session();

    $auth = $session->get("access_token");

    if ($auth !== null) {
      $owner = $this->model->getLoggedInUser($auth)->getLogin();

      $followers = $this->followers->getFollowers($owner);

      $followerLogins = array_map(function($item) { return $item["user"]; }, $followers);

      foreach ($users[$this->view->getUsersField()] as $key => $value) {
        if (in_array($value->getLogin(), $followerLogins)) {
          $value->setIsFollowed(true);
        }
      }
    }

    $context = array(
      $this->view->getUsersField() => $users[$this->view->getUsersField()],
      $this->view->getPagesField() => $pages,
      $this->view->getAuthField() => $auth
    );

    return $this->view->showAllUsers($context);
  }

  /**
   * Show action, display the given user.
   * @param String $user
   * @return The show view
   */
  public function show($user) {
    try {
      $userData = $this->model->getSingleUser($user);

      $repos = $this->model->getUserRepos($user);

      $followers = $this->model->getUserFollowers($user);

    } catch (\Exception $e) {
      return $this->errorView->showPageNotFound("/users/" . $user);
    }

    $session = new Session();

    $auth = $session->get("access_token");

    $context = array(
      $this->view->getUserField() => $userData,
      $this->view->getReposField() => $repos,
      $this->view->getFollowersField() => $followers,
      $this->view->getAuthField() => $auth
    );

    return $this->view->showSingleUser($context);
  }

  public function search() {
    $searchQuery = $this->view->getSearchBy();

    $users = null;

    if ($searchQuery) {
      $users = $this->model->searchUsers($searchQuery);
    }

    $session = new Session();

    $auth = $session->get("access_token");

    if ($auth !== null && $users !== null) {
      $owner = $this->model->getLoggedInUser($auth)->getLogin();

      $followers = $this->followers->getFollowers($owner);

      $followerLogins = array_map(function($item) { return $item["user"]; }, $followers);

      foreach ($users as $key => $value) {
        if (in_array($value->getLogin(), $followerLogins)) {
          $value->setIsFollowed(true);
        }
      }
    }

    $context = array($this->view->getUsersField() => $users, $this->view->getAuthField() => $auth);

    return $this->view->showSearch($context);

  }

  public function follow($user) {
    $session = new Session();
    $accessToken = $session->get("access_token");

    if ($accessToken !== null) {
      $owner = $this->model->getLoggedInUser($accessToken)->getLogin();

      $errorMsg = $this->view->getErrorMessage();
      $successMsg = $this->view->getSuccessMessage();

      if ($this->followers->createFollower($owner, $user)) {
        $context = array($successMsg => "You now follow " . $user);
      }
      else {
        $context = array($errorMsg => "You already follow " . $user);
      }

      return $this->view->createFollower($context);
    }

    return $this->errorView->showPageNotFound("/follow/" . $user);
  }

  public function unFollow($user) {
    $session = new Session();
    $accessToken = $session->get("access_token");

    if ($accessToken !== null) {
      $owner = $this->model->getLoggedInUser($accessToken)->getLogin();

      $successMsg = $this->view->getSuccessMessage();
      $errorMsg = $this->view->getErrorMessage();

      if ($this->followers->removeFollower($owner, $user)) {
        $context = array($successMsg => "You no longer follow " . $user);
      }
      else {
        $context = array($errorMsg => "You do not follow " . $user . " so you cannot unfollow");
      }

      return $this->view->createFollower($context);
    }

    return $this->errorView->showPageNotFound("/unfollow/" . $user);
  }
}
