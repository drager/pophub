<?php

namespace PopHub\Test;

require __DIR__.'/../../bootstrap/start.php';

use PopHub\Model;

class SessionTest extends \PHPUnit_Framework_TestCase {

  private $model;

  public function testCanSetAndDestroySession() {
    $session = new Model\Session();

    $expectedArray = array("login" => "jh222xk");

    $session->set("login", "jh222xk");

    $this->assertEquals($expectedArray["login"], $session->get("login"));

    $session->destroy("login");

    $this->assertNull($session->get("login"));
  }

  public function testCanGetSession() {

    $session = new Model\Session();

    $expectedArray = array("session_value" => "some_value");

    $session->set("some_session", "some_value");

    $this->assertEquals($expectedArray["session_value"],
      $session->get("some_session"));
  }

}
