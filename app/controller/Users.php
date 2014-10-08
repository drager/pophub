<?php

namespace PopHub\Controller;

use Kagu\Config\Config;

use PopHub\Model;
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
   * @param Integer $page 
   * @return The show all users view
   */
  public function index() {
    $page = $this->view->getPage();
    if ($this->view->getSortBy() === "repos" ) {
      $sortBy = $this->view->getSortBy();
      $users = $this->model->getAllUsers($page, $sortBy);
    }
    else {
      $users = $this->model->getAllUsers($page);
    }

    return $this->view->showAllUsers(array(
      "users" => $users["body"],
      "pages" => $users["pages"]
    ));
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

    return $this->view->show(array(
      "user" => $userData,
      "repos" => $repos,
      "followers" => $followers
    ));
  }
}