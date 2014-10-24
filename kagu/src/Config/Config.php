<?php

namespace Kagu\Config;

/**
 * Loads a configuration file
 * @package Kagu Config
 * @author Jesper Håkansson <jh222xk@student.lnu.se>
 */
class Config {

  private $configData;

  /**
   * @param String $fileName
   */
  public function __construct($fileName) {

    if (file_exists($fileName) === false) {
      throw new \InvalidArgumentException("The file name: {$fileName} does not exist");
    }

    $file = require $fileName;

    if ($file === null || is_array($file) === false) {
      throw new \InvalidArgumentException("The file does not return an array");
    }

    $this->configData = $file;
  }

  /**
   * Get the given configuration data according to index
   * @param String $index
   * @return String
   */
  public function get($index) {
    if (isset($this->configData[$index]) === false) {
      throw new \InvalidArgumentException("The index: {$index} does not exist");
    }

    return $this->configData[$index];
  }
}