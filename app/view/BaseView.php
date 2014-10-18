<?php

namespace PopHub\View;

use Twig_Loader_Filesystem;
use Twig_Environment;
use Kagu\View\View;

class BaseView extends View {

  protected function setup() {
    $this->setTemplatePath(__DIR__."/../templates/");

    $loader = new Twig_Loader_Filesystem($this->template);

    $this->engine = new Twig_Environment($loader);

    $this->urlTo();
  }
}