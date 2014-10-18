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
    $this->model = new Model\Github($config->get("GITHUB_CLIENT_ID"), $config->get("GITHUB_CLIENT_SECRET"), $config->get("GITHUB_CALLBACK_URL"));
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
          $users = $this->model->getAllUsers($page);
        }
      }
    } catch (HttpStatus404Exception $e) {
      return $this->errorView->showPageNotFound("/users/?page=" . $page);
    }

    // RESPONSE FEL FRÅN GITHUB?

    // ^GITHUB SKICKAR BARA UT 1000 RESULTAT…

    $auth = Session::get("access_token");

    $context = array(
      "users" => $users["body"],
      "pages" => $users["pages"],
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
      $repos = $this->model->getUsersRepos($user);
      $followers = $this->model->getUserFollowers($user);
    } catch (\Exception $e) {
      return $this->errorView->showPageNotFound("/users/" . $user);
    }

    $auth = Session::get("access_token");

    $context = array(
      "user" => $userData,
      "repos" => $repos,
      "followers" => $followers,
      "authenticated" => $auth
    );

    return $this->view->showSingleUser($context);
  }
}