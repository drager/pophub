<?php

namespace PopHub\View;

class Home extends BaseView {

  private $auth = "authenticated";
  private $search_q = "search_q";

  /**
   * The home view
   * @return void
   */
  public function showHome(array $context) {
    echo $this->render('home.html', array(
      $this->auth => $context[$this->auth],
      $this->search_q => $context[$this->search_q]
    ));
  }

  public function getAuthField() {
    return $this->auth;
  }
  public function getSearchField() {
    return $this->search_q;
  }
}