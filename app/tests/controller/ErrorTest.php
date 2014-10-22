<?php

namespace PopHub\Test;

require __DIR__.'/../../bootstrap/start.php';

use PopHub\Controller;
use PopHub\View;

class ErrorTest extends \PHPUnit_Framework_TestCase {

  public function testCanCallServerError() {
    $viewMock = $this->getMock('PopHub\View\Error', array('showServerError'));
    $viewMock->expects($this->once())->method('showServerError');

    $controller = new Controller\Error($viewMock);
    $controller->serverError();
  }

  public function testCanCallPageNotFoundError() {
    $viewMock = $this->getMock('PopHub\View\Error', array('showPageNotFound'));
    $viewMock->expects($this->once())->method('showPageNotFound');

    $controller = new Controller\Error($viewMock);
    $controller->pageNotFoundError("1");
  }

}