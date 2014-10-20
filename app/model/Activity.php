<?php

namespace PopHub\Model;

class Activity {

  private $user;

  private $repo;

  private $type;

  private $payload;

  private $created;

  /**
   * @param User $user
   * @param Repo $repo
   * @param String $type
   * @param String $payload
   * @param String $created
   * @return void
   */
  public function __construct(User $user, Repo $repo, $type, $payload, $created) {
    $this->user = $user;
    $this->repo = $repo;
    $this->type = $type;
    $this->payload = $payload;
    $this->created = $created;
  }

  /**
   * @return User object
   */
  public function getUser() {
    return $this->user;
  }

  /**
   * @return Repo object
   */
  public function getRepo() {
    return $this->repo;
  }

  /**
   * @return String
   */
  public function getType() {
    return $this->type;
  }

  /**
   * @return String
   */
  public function getPayload() {
    return $this->payload;
  }

  /**
   * @return String
   */
  public function getCreated() {
    return $this->created;
  }
}