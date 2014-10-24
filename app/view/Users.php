<?php

namespace PopHub\View;

class Users extends BaseView {

  private $errorView;

  private $cookie;

  private $errorMsg = "Message::Failure";
  private $successMsg = "Message::Success";
  private $searchFieldName = "q";

  private $users = "users";
  private $repos = "repos";
  private $followers = "followers";
  private $user = "user";
  private $auth = "authenticated";
  private $pages = "pages";
  private $sort = "sort";
  private $message = "message";
  private $search_q = "search_q";
  private $search_value = "search_value";

  public function __construct() {
    $this->errorView = new Error();
    $this->cookie = new CookieJar();
  }

  public function getErrorMessage() {
    return $this->errorMsg;
  }

  public function getSuccessMessage() {
    return $this->successMsg;
  }

  /**
   * View for showing all the users
   * @param Array $context
   * @return void
   */
  public function showAllUsers(array $context) {
    $page = $this->getPage();

    $sort = $this->getSortBy();

    $pages = $context["pages"];

    $message = array(
      "success" => $this->cookie->get($this->successMsg),
      "failure" => $this->cookie->get($this->errorMsg),
    );

    // Check for see if we tried to get a page that does not exist.
    if ($page > $pages->getNumPages()) {
      return $this->errorView->showPageNotFound("/users/?page=" . $page);
    }

    // var_dump(count($context["users"]));
    // die;

    echo $this->render('users.html', array(
      $this->users => $context[$this->users],
      $this->auth => $context[$this->auth],
      $this->pages => $pages,
      $this->sort => $sort,
      $this->message => $message,
      $this->search_q => $this->searchFieldName,
      $this->search_value => $this->getSearchBy()
    ));
  }

  /**
   * View for showing a single user
   * @param Array $context
   * @return void
   */
  public function showSingleUser(array $context) {

    echo $this->render('show_user.html', array(
      $this->user => $context[$this->user],
      $this->repos => $context[$this->repos],
      $this->followers => $context[$this->followers],
      $this->auth => $context[$this->auth],
      $this->search_q => $this->searchFieldName,
      $this->search_value => $this->getSearchBy()
    ));
  }

  public function showSearch(array $context) {
    echo $this->render('search.html', array(
      $this->users => $context[$this->users],
      $this->auth => $context[$this->auth],
      $this->search_q => $this->searchFieldName,
      $this->search_value => $this->getSearchBy()
    ));
  }

  public function createFollower(array $context) {
    if (isset($context[$this->successMsg])) {
      $this->cookie->set($this->successMsg, $context[$this->successMsg]);
    } else if (isset($context[$this->errorMsg])) {
      $this->cookie->set($this->errorMsg, $context[$this->errorMsg]);
    }
    
    header('Location: ' . "/users/", true, 303);
    die;
  }

  public function getSearchFieldName() {
    return $this->searchFieldName;
  }

  /**
   * @return String
   *  DRY?
   */
  public function getSearchBy() {
    if (isset($_GET[$this->searchFieldName])) {
      return $_GET[$this->searchFieldName];
    }
  }

  /**
   * @return String
   *  DRY?
   */
  public function getLanguage() {
    if (isset($_GET["language"])) {
      return $_GET["language"];
    }
  }

  /**
   * @return String
   * DRY?
   */
  public function getSortBy() {
    if (isset($_GET["sort_by"])) {
      return $_GET["sort_by"];
    }
  }

  /**
   * @return String
   * DRY?
   */
  public function getPage() {
    if (isset($_GET["page"])) {
      return $_GET["page"];
    }
  }


  public function getUsersField() {
    return $this->users;
  }
  public function getReposField() {
    return $this->repos;
  }
  public function getFollowersField() {
    return $this->followers;
  }
  public function getUserField() {
    return $this->user;
  }
  public function getAuthField() {
    return $this->auth;
  }
  public function getPagesField() {
    return $this->pages;
  }
  public function getSortField() {
    return $this->sort;
  }
  public function getMessageField() {
    return $this->message;
  }
  public function getSearchField() {
    return $this->search_q;
  }
  public function getSearchValueField() {
    return $this->search_value;
  }
}