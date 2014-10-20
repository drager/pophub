<?php

namespace PopHub\Model;

class Repo {

  private $name;

  private $owner;

  /**
   * @param String $name
   * @param User $owner
   * @return void
   */
  public function __construct($name, User $owner) {
    $this->name = $name;
    $this->owner = $owner;
  }

  /**
   * @return String
   */
  public function getName() {
    return $this->name;
  }

  /**
   * @return User object
   */
  public function getOwner() {
    return $this->owner;
  }
}