<?php

namespace PopHub\Test;

require_once __DIR__."/../../../kagu/src/Exception/Exceptions.php";

require __DIR__.'/../../bootstrap/start.php';

use Kagu\Config;
use Kagu\Exception\MissingCredentialsException;

use PopHub\Model;



// var_dump(__DIR__.'/../../bootstrap/start.php');


class GithubTest extends \PHPUnit_Framework_TestCase {

  private $github;

  public function setUp() {
    $this->config = new Config\Config(__DIR__."/../../config/app.php");
    $this->github = new Model\Github($this->config->get("GITHUB_CLIENT_ID"), $this->config->get("GITHUB_CLIENT_SECRET"), $this->config->get("GITHUB_CALLBACK_URL"));
  }

  public function tearDown() {
    $this->github = null;
  }


  /**
  * @expectedException        InvalidArgumentException
  * @expectedExceptionMessage GITHUB_CLIENT_ID needs to be set!
  */
  public function testConstructorCallsInternalMethods() {
    $github = new Model\Github(null, null, null);
  }

  /**
  * @expectedException        InvalidArgumentException
  * @expectedExceptionMessage GITHUB_CLIENT_SECRET needs to be set!
  */
  public function testConstructorWithoutClientSecret() {
    $github = new Model\Github("SOME_CLIENT_ID", null, null);
  }

  /**
  * @expectedException        InvalidArgumentException
  * @expectedExceptionMessage GITHUB_CALLBACK_URL needs to be set!
  */
  public function testConstructorWithoutCallbackURL() {
    $github = new Model\Github("SOME_CLIENT_ID", "WONDERFUL_SECRET", null);
    // WONDERFUL_SECRET
  }

  public function testCanGetAllUsersWithoutSorting() {
    $expectedArray = array("login" => "torvalds");

    $this->assertEquals($expectedArray["login"], $this->github->getAllUsers()["body"]->items[0]->login);
  }

  public function testCanGetAllUsersSortingByRepos() {
    $sortBy = "repos";

    $expectedArray = array("login" => "visionmedia");

    $this->assertEquals($expectedArray["login"], $this->github->getAllUsers(null, $sortBy)["body"]->items[0]->login);
  }

  public function testCanGetAllUsersWithPagingButWithoutSorting() {
    $page = "2";

    $expectedArray = array("login" => "miyagawa");

    $this->assertEquals($expectedArray["login"], $this->github->getAllUsers($page)["body"]->items[0]->login);
  }

  public function testCanGetAllUsersWithPagingAndSorting() {
    $page = "3";

    $sortBy = "repos";

    $users = $this->github->getAllUsers($page, $sortBy)["body"]->items;

    $expectedArray = array("login" => "boostbob");

    $hasFound = false;
    foreach($users as $user) {
      if ($expectedArray["login"] == $user->login) {
        $hasFound = true;
      }
    }

    $this->assertTrue($hasFound);
  }

  public function testCanGetSingleUser() {
    $username = "jh222xk";

    $expectedArray = array("login" => "jh222xk", "another_login" => "jh22213");

    $this->assertEquals($expectedArray["login"], $this->github->getSingleUser($username)->login);

    $this->assertNotEquals($expectedArray["another_login"], $this->github->getSingleUser($username)->login);
  }

  public function testCanGetUserRepos() {
    $username = "jh222xk";

    $expectedRepoArray = array("name" => "pophub");

    $repos = $this->github->getUsersRepos($username);

    $hasFound = false;
    foreach($repos as $repo) {
      if ($expectedRepoArray["name"] == $repo->name) {
        $hasFound = true;
      }
    }

    $this->assertTrue($hasFound);
  }

  public function testCanGetAllUsersWithLanguageSorting() {
    $expectedArray = array("login" => "JeffreyWay");

    $this->assertEquals($expectedArray["login"], $this->github->getAllUsers(null, null, "php")["body"]->items[1]->login);
  }

  public function testCanGetUserFollowers() {
    $username = "torvalds";

    $expectedArray = array("login" => "jeycin");

    $followers = $this->github->getUserFollowers($username);

    $this->assertEquals($expectedArray["login"], $followers[0]->login);
  }

  public function testUserCanGetAuthenticated() {
    $auth = $this->github->authorize();

    $url = "https://github.com/login/oauth/authorize?client_id="
      . $this->config->get("GITHUB_CLIENT_ID")  . "&redirect_uri=" . $this->config->get("GITHUB_CALLBACK_URL");

    $this->assertEquals($auth, $url);

    $token = $this->github->postAccessToken("some_token");
  }

}


