<?php

namespace PopHub\Test;

require __DIR__.'/../../bootstrap/start.php';

use PopHub\Model;

class PagesTest extends \PHPUnit_Framework_TestCase {

  private $model;

  public function testCanGetAvailablePages() {
    $first = "1";
    $prev = null;
    $next = "2";
    $last = "10";
    $numPages = 10;

    $expectedArray = array("first" => $first, "prev" => $prev,
      "next" => $next, "last" => $last, "numpages" => $numPages);

    $model = new Model\Pages($first, $prev, $next, $last);

    $this->assertEquals($expectedArray["first"], $model->getFirst());
    $this->assertEquals($expectedArray["prev"], $model->getPrev());
    $this->assertEquals($expectedArray["next"], $model->getNext());
    $this->assertEquals($expectedArray["last"], $model->getLast());
    $this->assertEquals($expectedArray["numpages"], $model->getNumPages());
  }

}


