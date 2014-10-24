<?php

namespace PopHub\View;

class Users extends BaseView {

  private $errorView;

  private $cookie;

  private $errorMsg = "Message::Failure";

  private $successMsg = "Message::Success";

  private $searchFieldName = "q";

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
      "users" => $context["users"],
      "authenticated" => $context["authenticated"],
      "pages" => $pages,
      "sort" => $sort,
      "message" => $message,
      "search_q" => $this->searchFieldName,
      "search_value" => $this->getSearchBy()
    ));
  }

  /**
   * View for showing a single user
   * @param Array $context
   * @return void
   */
  public function showSingleUser(array $context) {

    echo $this->render('show_user.html', array(
      "user" => $context["user"],
      "repos" => $context["repos"],
      "followers" => $context["followers"],
      "authenticated" => $context["authenticated"],
      "search_q" => $this->searchFieldName,
      "search_value" => $this->getSearchBy()
    ));
  }

  public function showSearch(array $context) {
    echo $this->render('search.html', array(
      "users" => $context["users"],
      "authenticated" => $context["authenticated"],
      "search_q" => $this->searchFieldName,
      "search_value" => $this->getSearchBy()
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
}