<?php

namespace PopHub\Model;

use Kagu\Database\MysqlAdapter;
use Kagu\Config\Config;

class Followers extends MysqlAdapter {

  private $table = "followers";
  private $primaryKey = "id";
  private $user = "user";
  private $owner = "owner";
  private $created = "created_at";

  /**
   * @param Config $config
   * @return void
   */
  public function __construct(Config $config) {
    $this->config = $config;
  }

  public function createTable() {
    $db = $this->connect();
    $result = $this->create($this->table, array(
      $this->primaryKey => "INT NOT NULL AUTO_INCREMENT PRIMARY KEY",
      $this->user => "VARCHAR(255) NOT NULL",
      $this->owner => "VARCHAR(255) NOT NULL",
      $this->created => "DATETIME NOT NULL"
    ));
  }

  /**
   * @param String $user
   * @return Array
   */
  public function getFollowers($user) {
    $db = $this->connect();

    $result = $this->select($this->table, $this->user, $this->owner, array($user), "created_at");

    return $result;
  }

  public function createFollower($owner, $user) {
    $db = $this->connect();

    if ($this->contains($user, $owner) === false) {
      $result = $this->insert($this->table, array($this->user => $user, $this->owner => $owner));
      return true;
    }

    return false;
  }

  public function contains($user, $owner) {
    $followers = $this->getFollowers($owner);

    if (count($followers) >= 1) {
      foreach($followers as $value) {
        if ($value["user"] === $user) {
          return true;
        }
      }
    }

    return false;
  }
}