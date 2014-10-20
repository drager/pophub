<?php

namespace Kagu\Http;

require_once __DIR__.'/../Exception/Exceptions.php';

use Kagu\Exception\HttpStatus404Exception;

/**
 * Http Response
 * @package Kagu Http
 * @author Jesper Håkansson <jh222xk@student.lnu.se>
 */
class Response {

  private $body;
  private $headers = array();

  /**
   * Standard HTTP status codes.
   */
  const STATUS_404 = 404;
  const STATUS_401 = 401;
  const STATUS_422 = 422;
  const STATUS_500 = 500;

  /**
   * @param type $body
   * @param Array $headers
   */
  function __construct($body, array $headers) {
    $this->setBody($body);
    $this->setHeaders($headers);

    $status = $this->getHeader("Status");

    $status = (int)strtok($status, " ");

    if ($status === self::STATUS_404) {
      throw new HttpStatus404Exception("Page not found.");
    }

    if ($status === self::STATUS_401) {
      throw new HttpStatus404Exception("Unauthorized.");
    }

    if ($status === self::STATUS_422) {
      throw new HttpStatus404Exception("Unprocessable Entity.");
    }

    if ($status === self::STATUS_500) {
      throw new \Exception("Something unexpected happend.");
    }
  }

  /**
   * @return String body
   */
  public function getBody() {
    return $this->body;
  }

  /**
   * Get the given header specified by a key.
   * @param String $key
   * @return String
   */
  public function getHeader($key) {
    if (isset($this->headers[$key]) === false) {
      return null;
    }

    return $this->headers[$key];
  }

  /**
   * @param String $body
   */
  private function setBody($body) {
    $this->body = $body;
  }

  /**
   * @param Array $headers
   */
  private function setHeaders(array $headers) {
    $this->headers = $this->parseHeaders($headers);
  }

  /**
   * @return Array
   */
  public function getHeaders() {
    return $this->headers;
  }

  /**
   * Takes the response headers and parses them so we later on
   * can get them specified by String key name rather than by
   * numeric index. Instead ["SERVER"] rather than [1].
   * @param Array $headers
   * @return Array
   */
  private function parseHeaders(array $headers) {
    $headersArr = array();

    foreach ($headers as $i => $header) {
      if ($header === "") {
        continue;
      }

      $parts = explode(": ", $header);

      if (isset($parts[1])) {
        $headersArr[$parts[0]] = $parts[1];
      }
      else {
        $headersArr[$i] = $header;
      }
    }

    return $headersArr;
  }
}
