<?php

namespace PopHub\View;

class Users extends BaseView {

  private $errorView;

  public function __construct() {
    $this->errorView = new Error();
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
      "sort" => $sort
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
      "authenticated" => $context["authenticated"]
    ));
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