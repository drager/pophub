<?php

namespace PopHub\Test;

require __DIR__.'/../../bootstrap/start.php';

use PopHub\Model;

class ActivityTest extends \PHPUnit_Framework_TestCase {

  private $model;

  public function testCanGetAUsersActivityInformation() {
    $repoName = "PopHub";
    $login = "jh222xk";

    $user = new Model\User($login, null, null, null, null, null);
    $repo = new Model\Repo($repoName, $user);
    $type = "PushEvent";
    $payload = "commit";
    $created = "2014-10-20";

    $expectedArray = array("user" => $user, "repo" => $repo,
      "type" => $type, "payload" => $payload, "created" => $created);

    $model = new Model\Activity($user, $repo, $type, $payload, $created);

    $this->assertEquals($expectedArray["user"], $model->getUser());
    $this->assertEquals($expectedArray["repo"], $model->getRepo());
    $this->assertEquals($expectedArray["type"], $model->getType());
    $this->assertEquals($expectedArray["payload"], $model->getPayload());
    $this->assertEquals($expectedArray["created"], $model->getCreated());
  }

}


