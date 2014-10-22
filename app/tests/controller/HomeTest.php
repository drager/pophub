<?php

namespace PopHub\Test;

require __DIR__.'/../../bootstrap/start.php';

use PopHub\Controller;
use PopHub\View;

class HomeTest extends \PHPUnit_Framework_TestCase {

  public function testCanCallHome() {
    $viewMock = $this->getMock('PopHub\View\Home', array('showHome'));
    $viewMock->expects($this->once())->method('showHome');

    $controller = new Controller\Home($viewMock);
    $controller->index();
  }

}