<?php

namespace PopHub\Test;

require_once __DIR__."/../../../kagu/src/Exception/Exceptions.php";

require __DIR__.'/../../bootstrap/start.php';

use PopHub\Controller;

class AuthTest extends \PHPUnit_Framework_TestCase {

  public function testCanAuthenticateUser() {
    $configMock = $this->getMock('Kagu\Config\Config', null, array(__DIR__."/../../config/app.php"));

    $modelMock = $this->getMock('PopHub\Model\GitHub', null, array($configMock));

    $adapterMock = $this->getMock('PopHub\Model\GitHubAdapter', array('authorize'), array($modelMock));
    $adapterMock->expects($this->any())->method('authorize');

    $controller = new Controller\Auth($adapterMock);
    $controller->index();
  }

}