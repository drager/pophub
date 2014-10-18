<?php

namespace PopHub\View;

class Auth extends BaseView {

  /**
   * View for showing the currently logged in user
   * @param Array $context
   * @return void
   */
  public function showLoggedIn(array $context) {
    echo $this->render('logged_in.html', array(
      "user" => $context["user"],
      "authenticated" => $context["authenticated"],
      "followers" => $context["followers"],
      "events" => $context["events"]
    ));
  }
}