<?php

namespace PopHub\View;

class Home extends BaseView {

  /**
   * The home view
   * @return void
   */
  public function showHome(array $context) {
    echo $this->render('home.html', array(
      "authenticated" => $context["authenticated"],
      "search_q" => $context["search_q"]
    ));
  }
}