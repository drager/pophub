<?php

namespace Kagu\Controller;

use Twig_Loader_Filesystem;
use Twig_Environment;
use Twig_SimpleFunction;

abstract class Controller {
  
  protected $engine;

  protected $template;

  /**
   * Setup the template loader, path and engine
   * for use.
   * @return void
   */
  protected abstract function setup();

  /**
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

  /**
   * Sets the template path so the engine can find'em
   * @param String $path
   * @return void
   */
  protected function setTemplatePath($path) {
    if (file_exists($path) === false ) {
      throw new \Exception("The directory [$path] does not exist!");
    }

    $this->template = $path;
  }

  /**
   * Template function for getting the full url path.
   * @return String
   */
  protected function urlTo() {

    $function = new Twig_SimpleFunction('url_to', function ($name) {

      // If the name starts with "/", remove it.
      if (preg_match("#^/#", $name) === 1) {
        $name = substr($name, 1);
      }

      $url = "http";

      // Check if we have HTTPS.
      if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
        $url .= "s";
      }

      $url .= "://";

      if ($_SERVER["SERVER_PORT"] != "80") {
        $url .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"];
      } else {
        $url .= $_SERVER["SERVER_NAME"];
      }

      return $url . "/" . $name;
    });

    $this->engine->addFunction($function);

  }
}