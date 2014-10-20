<?php

namespace PopHub\Test;

require __DIR__.'/../../bootstrap/start.php';

use PopHub\Model;

class UserTest extends \PHPUnit_Framework_TestCase {

  private $userModel;

  public function testCanGetAUsersInformation() {
    $login = "jh222xk";
    $name = "Jesper";
    $email = "jh222xk@student.lnu.se";
    $location = "Sweden";
    $joined = "2014-10-20";
    $avatar = "https://domain.com/avatar_url.png";

    $expectedArray = array("login" => $login, "name" => $name, "email" => $email,
      "location" => $location, "joined" => $joined, "avatar" => $avatar);

    $userModel = new Model\User($login, $name, $email,
      $location, $joined, $avatar);

    $this->assertEquals($expectedArray["login"], $userModel->getLogin());
    $this->assertEquals($expectedArray["name"], $userModel->getName());
    $this->assertEquals($expectedArray["email"], $userModel->getEmail());
    $this->assertEquals($expectedArray["location"], $userModel->getLocation());
    $this->assertEquals($expectedArray["joined"], $userModel->getJoined());
    $this->assertEquals($expectedArray["avatar"], $userModel->getAvatar());
  }

}


