<?php

namespace PopHub\Controller;

use PopHub\View;

class Home {
  private $view;

  function __construct() {
    $this->view = new View\Home();
  }

  public function index() {
    return $this->view->showHome();
  }
}