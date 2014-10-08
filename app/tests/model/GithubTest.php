<?php

namespace PopHub\Test;

use PopHub\Model;

require __DIR__.'/../../bootstrap/start.php';

// var_dump(__DIR__.'/../../bootstrap/start.php');


class GithubTest extends \PHPUnit_Framework_TestCase {

  public function testCanGetAllUsersWithoutSorting() {
    $github = new Model\Github();

    $expectedArray = array("login" => "torvalds");

    $this->assertEquals($expectedArray["login"], $github->getAllUsers()["body"]->items[0]->login);
  }

  public function testCanGetAllUsersSortingByRepos() {
    $github = new Model\Github();

    $sortBy = "repos";

    $expectedArray = array("login" => "visionmedia");

    $this->assertEquals($expectedArray["login"], $github->getAllUsers(null, $sortBy)["body"]->items[0]->login);
  }

  public function testCanGetAllUsersWithPagingButWithoutSorting() {
    $github = new Model\Github();

    $page = "2";

    $expectedArray = array("login" => "miyagawa");

    $this->assertEquals($expectedArray["login"], $github->getAllUsers($page)["body"]->items[0]->login);
  }

  public function testCanGetAllUsersWithPagingAndSorting() {
    $github = new Model\Github();

    $page = "3";

    $sortBy = "repos";

    $expectedArray = array("login" => "boostbob");

    $this->assertEquals($expectedArray["login"], $github->getAllUsers($page, $sortBy)["body"]->items[0]->login);
  }

  public function testCanGetSingleUser() {
    $github = new Model\Github();

    $username = "jh222xk";

    $expectedArray = array("login" => "jh222xk", "another_login" => "jh22213");

    $this->assertEquals($expectedArray["login"], $github->getSingleUser($username)->login);

    $this->assertNotEquals($expectedArray["another_login"], $github->getSingleUser($username)->login);
  }

}