<?php

namespace PopHub\View;

class Users {


  public function getLanguage() {
    if (isset($_GET["language"])) {
      return $_GET["language"];
    }
  }

  /**
   * @return String
   */
  public function getSortBy() {
    if (isset($_GET["sort_by"])) {
      return $_GET["sort_by"];
    }
    else {
      return "";
    }
  }

  /**
   * @return String
   */
  public function getPage() {
    if (isset($_GET["page"])) {
      return $_GET["page"];
    }
    else {
      return "";
    }
  }
}