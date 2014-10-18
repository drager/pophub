<?php

namespace PopHub\View;

class Error extends BaseView {

  /**
   * Shows the 500 error page.
   * @return void
   */
  public function showServerError() {
    echo $this->render("500.html");
  }

  /**
   * Shows the 404 not found page.
   * @param String $page
   * @return void
   */
  public function showPageNotFound($page) {
    echo $this->render("404.html", array("page" => $page));
  }
}