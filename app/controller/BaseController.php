<?php

namespace PopHub\Controller;

use Twig_Loader_Filesystem;
use Twig_Environment;
use Kagu\Controller\Controller;

class BaseController extends Controller {

  protected function setup() {
    $this->setTemplatePath(__DIR__."/../view/");

    $loader = new Twig_Loader_Filesystem($this->template);

    $this->engine = new Twig_Environment($loader);

    $this->urlTo();
  }
}