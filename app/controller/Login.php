<?php

namespace PopHub\Controller;

use PopHub\Model;

class Login {
  /**
   * @var Userview
   */
  private $view;

  /**
   * @var Usermodel
   */
  private $model;
  
  function __construct() {
    $this->model = new Model\Github();
  }

  public function index() {
    return $this->model->authorize();
  }
}