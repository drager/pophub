<?php

namespace Kagu\Controller;

use Twig_Loader_Filesystem;
use Twig_Environment;

abstract class Controller {
  
  protected $engine;

  protected $loader;

  protected $template;

  /**
   * Setup the template loader, path and engine
   * for use.
   * @return void
   */
  protected abstract function setup();

  /**
   * Description
   * @param String $template 
   * @param Array $data 
   * @return type
   */
  protected function render($template, array $data = null) {
    $this->setup();

    if ($data === null) {
      return $this->engine->render($template);
    }

    return $this->engine->render($template, $data);
  }

  protected function setTemplatePath($path) {
    if (file_exists($path) === false ) {
      throw new \Exception("The directory [$path] does not exist!");
    }

    $this->template = $path;
  }
}