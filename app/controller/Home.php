<?php

namespace PopHub\Controller;

use PopHub\View;
use PopHub\Model\Session;


class Home extends BaseController {
  private $view;

  function __construct() {
    $this->view = new View\Home();
  }

  public function index() {
    $auth = Session::get("access_token");

    echo $this->render('home.html', array(
      "authenticated" => $auth
    ));
  }
}