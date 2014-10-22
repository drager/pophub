<?php

namespace PopHub\Test;

require_once __DIR__."/../../../kagu/src/Exception/Exceptions.php";

require __DIR__.'/../../bootstrap/start.php';

use Kagu\Config;
use Kagu\Exception\MissingCredentialsException;
use Kagu\Cache;

use PopHub\Model;

class GithubTest extends \PHPUnit_Framework_TestCase {

  private $github;

  public function setUp() {
    $this->config = new Config\Config(__DIR__."/../../config/app.php");
    $this->github = new Model\GithubAdapter(new Model\Github($this->config));
  }

  public function tearDown() {
    $this->github = null;
    $this->config = null;
  }

  public function testCanGetAllUsersWithoutSorting() {
    $expectedArray = array("login" => "torvalds");

    $users = $this->github->getAllUsers(null, "followers");

    $pages = $users["pages"];

    $this->assertEquals("2", $pages->getNext());
    $this->assertEquals("10", $pages->getLast());

    $this->assertEquals($expectedArray["login"], $users["users"][0]->getLogin());
  }

  public function testCanGetAllUsersSortingByRepos() {
    $sortBy = "repos";

    $expectedArray = array("login" => "substack");

    $users = $this->github->getAllUsers(null, $sortBy);

    $this->assertEquals($expectedArray["login"], $users["users"][0]->getLogin());
  }

  public function testCanGetAllUsersWithPagingButWithoutSorting() {
    $page = "2";

    $expectedArray = array("login" => "chenshuo");

    $users = $this->github->getAllUsers($page, "followers");

    $hasFound = false;
    foreach($users["users"] as $user) {
      if ($expectedArray["login"] == $user->getLogin()) {
        $hasFound = true;
      }
    }

    $this->assertTrue($hasFound);
  }

  public function testCanGetAllUsersWithPagingAndSorting() {
    $page = "3";

    $sortBy = "repos";

    $users = $this->github->getAllUsers($page, $sortBy);

    $expectedArray = array("login" => "boostbob");

    $hasFound = false;
    foreach($users["users"] as $user) {
      if ($expectedArray["login"] == $user->getLogin()) {
        $hasFound = true;
      }
    }

    $this->assertTrue($hasFound);
  }

  public function testCanGetSingleUser() {
    $username = "jh222xk";

    $expectedArray = array("login" => "jh222xk", "another_login" => "jh22213");

    $this->assertEquals($expectedArray["login"], $this->github->getSingleUser($username)->getLogin());

    $this->assertNotEquals($expectedArray["another_login"], $this->github->getSingleUser($username)->getLogin());
  }

  public function testCanGetUserRepos() {
    $username = "jh222xk";

    $expectedRepoArray = array("name" => "pophub");

    $repos = $this->github->getUserRepos($username);

    $hasFound = false;
    foreach($repos as $repo) {
      if ($expectedRepoArray["name"] == $repo->getName()) {
        $hasFound = true;
      }
    }

    $this->assertTrue($hasFound);
  }

  public function testCanGetAllUsersWithLanguageSorting() {
    $expectedArray = array("login" => "JeffreyWay");

    $this->assertEquals($expectedArray["login"], $this->github->getAllUsers(null, null, "php")["users"][1]->getLogin());
  }

  public function testCanGetUserFollowers() {
    $username = "torvalds";

    $expectedArray = array("login" => "jeycin");

    $followers = $this->github->getUserFollowers($username);

    $this->assertEquals($expectedArray["login"], $followers[0]->getUser()->getLogin());
  }

  /**
  * @expectedException        Exception
  */
  public function testUserCanNOTGetAuthenticatedWithInvalidToken() {

    // GITHUB_CLIENT_ID is not found since it is in env vars
    // and therefore we will get the exception "Page not found"
    // instead. Real exception would be "Code invalid!".

    $auth = $this->github->authorize();

    $url = "https://github.com/login/oauth/authorize?client_id="
      . $this->config->get("GITHUB_CLIENT_ID")  . "&redirect_uri=" . $this->config->get("GITHUB_CALLBACK_URL");

    $this->assertEquals($auth, $url);

    $token = $this->github->postAccessToken("some_token");
  }

  public function testCanGetAUsersActivity() {
    $user = "jh222xk";

    $expectedArray = array("login" => "jh222xk");

    $events = $this->github->getUserActivity($user);

    // Flush the cache
    $memcached = new Cache\MemcachedAdapter(new Cache\Memcached($this->config));
    $memcached->flush();

    $this->assertEquals($expectedArray["login"], $events[0]->getUser()->getLogin());
  }

  public function testCanSearchForUsers() {
    $user = "jh222xk";

    $expectedArray = array("login" => "jh222xk");

    $users = $this->github->searchUsers($user);

    $this->assertEquals($expectedArray["login"], $users[0]->getLogin());
  }

  public function testCanGetAllUsersWithoutSortingLimitedResult() {
    $expectedArray = array("login" => "torvalds");

    $users = $this->github->getAllUsers(null, "followers", null, 5000);

    $pages = $users["pages"];

    $this->assertEmpty($pages->getFirst());
    $this->assertEmpty($pages->getLast());

    $this->assertEquals($expectedArray["login"], $users["users"][0]->getLogin());
  }

  public function testCannotGetLoggedInUser() {
    $expectedArray = array("login" => "jh222xk");

    $user = $this->github->getLoggedInUser("some_invalid_token");

    $this->assertNull($user);
  }
}
