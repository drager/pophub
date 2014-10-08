<?php

namespace Kagu\Http;

use Kagu\Http\Response;
use Kagu\Exception\Exceptions\NotImplementedException;

/**
 * Http Request
 * @package Kagu Http
 * @author Jesper Håkansson <jh222xk@student.lnu.se>
 */
class Request {

  private $url;

  const NAME           = "Kagu";
  const VERSION        = 0.1;

  /**
   * Standard HTTP methods.
   */ 
  const METHOD_GET     = "GET";
  const METHOD_POST    = "POST";
  const METHOD_PUT     = "PUT";
  const METHOD_DELETE  = "DELETE";

  /**
   * @param String $url
   */
  public function __construct($url) {
    $this->setUrl($url);
  }

  /**
   * @param String $url
   */
  public function setUrl($url) {
    if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
      throw new \InvalidArgumentException("The [$url] is wrong formatted!");
    }
    $this->url = $url;
  }

  /**
   * @return String $url
   */
  public function getUrl() {
    return $this->url;
  }

  /**
   * @param String $method
   * @param Array $options
   * @return Response object
   */
  public function performRequest($method = self::METHOD_GET) {
    switch ($method) {
      case self::METHOD_POST:
        return $this->post($this->url);
        break;
      case self::METHOD_PUT:
        return $this->put($this->url);
        break;
      case self::METHOD_DELETE:
        return $this->delete($this->url);
        break;
      default:
        // Defaults to the get method.
        return $this->get($this->url);
        break;
    }
  }

  /**
   * @param String $method, defaults to the GET method.
   * @param Boolean $ignore_errors, defaults to true.
   * @param Integer $follow_location, defaults to 0.
   * @return Array
   */
  private function getContext($method = self::METHOD_GET, $ignore_errors = true, $follow_location = 0) {
    $context = array(
      "http" => array(
        "method"           => $method,
        "user_agent"       => self::NAME . self::VERSION,
        "ignore_errors"    => $ignore_errors,
        "follow_location"  => $follow_location
      )
    );
    return $context;
  }

  /**
   * @param Array $options 
   * @return Streaming object
   */
  private function createStream($options = array()) {
    return stream_context_create($options);
  }

  /**
   * Makes a http get request to then given url
   * @param String $url
   * @param Array $options
   * @return Response object
   */
  private function get($url) {
    $response = file_get_contents($url, false, $this->createStream($this->getContext()));

    $data = explode("\r", $response);

    $body = array_pop($data);

    if ($response === false) {
      throw new \Exception("Server not responding");
    }

    return new Response($body, $http_response_header);
  }

  /**
   * Makes a http post request to then given url
   * @param String $url
   * @return Response object
   */
  private function post($url) {
    throw new NotImplementedException("The " . self::METHOD_POST . " METHOD is not implemented just yet!");
  }

  /**
   * Makes a http put request to then given url
   * @param String $url
   * @return Response object
   */
  private function put($url) {
    throw new NotImplementedException("The " . self::METHOD_PUT . " METHOD is not implemented just yet!");
  }

  /**
   * Makes a http delete request to then given url
   * @param String $url
   * @return Response object
   */
  private function delete($url) {
    throw new NotImplementedException("The " . self::METHOD_DELETE . " METHOD is not implemented just yet!");
  }
}