<?php

namespace PopHub\Model;

class User {

  private $login;

  private $name;

  private $email;

  private $location;

  private $joined;

  private $avatar;

  /**
   * @param String $login
   * @param String $name
   * @param String $email
   * @param String $location
   * @param String $joined
   * @param String $avatar
   * @return void
   */
  public function __construct($login, $name, $email, $location, $joined, $avatar) {
    $this->login = $login;
    $this->name = $name;
    $this->email = $email;
    $this->location = $location;
    $this->joined = $joined;
    $this->avatar = $avatar;
  }

  /**
   * @return String
   */
  public function getLogin() {
    return $this->login;
  }

  /**
   * @return String
   */
  public function getAvatar() {
    return $this->avatar;
  }

  /**
   * @return String
   */
  public function getName() {
    return $this->name;
  }

  /**
   * @return String
   */
  public function getEmail() {
    return $this->email;
  }

  /**
   * @return String
   */
  public function getLocation() {
    return $this->location;
  }

  /**
   * @return String
   */
  public function getJoined() {
    return $this->joined;
  }

}