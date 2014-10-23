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

    $context = array(
      "users" => $users["users"],
      "pages" => $pages,
      "authenticated" => $auth
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
      "user" => $userData,
      "repos" => $repos,
      "followers" => $followers,
      "authenticated" => $auth
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

    $context = array("users" => $users, "authenticated" => $auth);

    return $this->view->showSearch($context);

  }
}




