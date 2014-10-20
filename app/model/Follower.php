<?php

namespace PopHub\Model;

class Follower {

  private $user;

  /**
   * @param User $user
   * @return void
   */
  public function __construct(User $user) {
    $this->user = $user;
  }

  /**
   * @return User object
   */
  public function getUser() {
    return $this->user;
  }
}