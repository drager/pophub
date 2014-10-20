<?php

namespace PopHub\Test;

require __DIR__.'/../../bootstrap/start.php';

use PopHub\Model;

class RepoTest extends \PHPUnit_Framework_TestCase {

  private $model;

  public function testCanGetAReposInformation() {
    $repoName = "PopHub";
    $login = "jh222xk";
    $owner = new Model\User($login, null, null, null, null, null);

    $expectedArray = array("name" => $repoName, "owner" => $owner);

    $model = new Model\Repo($repoName, $owner);

    $this->assertEquals($expectedArray["owner"], $model->getOwner());
    $this->assertEquals($expectedArray["name"], $model->getName());
  }

}


