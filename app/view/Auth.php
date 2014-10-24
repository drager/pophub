<?php

namespace PopHub\View;

class Auth extends BaseView {

  private $user = "user";
  private $auth = "authenticated";
  private $followers = "followers";
  private $events = "events";
  private $search_q = "search_q";
  private $search_value = "search_value";

  /**
   * View for showing the currently logged in user
   * @param Array $context
   * @return void
   */
  public function showLoggedIn(array $context) {
    echo $this->render('logged_in.html', array(
      $this->user => $context[$this->user],
      $this->auth => $context[$this->auth],
      $this->followers => $context[$this->followers],
      $this->events => $context[$this->events],
      $this->search_q => $context[$this->search_q],
      $this->search_value => $context[$this->search_value]
    ));
  }

  public function getFollowersField() {
    return $this->followers;
  }
  public function getUserField() {
    return $this->user;
  }
  public function getEventField() {
    return $this->events;
  }
  public function getAuthField() {
    return $this->auth;
  }
  public function getSearchField() {
    return $this->search_q;
  }
  public function getSearchValueField() {
    return $this->search_value;
  }
}