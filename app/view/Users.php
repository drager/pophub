<?php

namespace PopHub\View;

class Users extends BaseView {

  /**
   * View for showing all the users
   * @param Array $context
   * @return void
   */
  public function showAllUsers(array $context) {
    $page = $this->getPage();

    $pages = $this->constructPaging($context["pages"]);

    $sort = $this->getSortBy();

    // Check for see if we tried to get a page that does not exist.
    if ($page > $pages["num_of_pages"]) {
      return $this->errorView->showPageNotFound("/users/?page=" . $page);
    }

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
   * Removes the url from the items and just gets the numbers
   * @param Array $pages
   * @return Array
   */
  public function constructPaging($pages) {

    if (isset($pages["first"])) {
      $pages["first"] = preg_replace('/^.*page=\s*/', '', $pages["first"]);
    }

    if (isset($pages["prev"])) {
      $pages["prev"] = preg_replace('/^.*page=\s*/', '', $pages["prev"]);
    }

    if (isset($pages["next"])) {
      $pages["next"] = preg_replace('/^.*page=\s*/', '', $pages["next"]);
    }

    if (isset($pages["last"])) {
      $pages["last"] = preg_replace('/^.*page=\s*/', '', $pages["last"]);
    }

    $pages["num_of_pages"] = isset($pages["last"]) ? $pages["last"] : $pages["prev"] + 1;

    return $pages;
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