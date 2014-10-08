<?php

namespace PopHub\Model;

require_once '../kagu/src/Http/Request.php';
require_once '../kagu/src/Config/Config.php';
require_once '../kagu/src/Exception/Exceptions.php';

use Kagu\Http\Request;
use Kagu\Http\Response;
use Kagu\Config\Config;
use Kagu\Exception\Exceptions\MissingCredentialsException;
use Kagu\Exception\Exceptions\RateLimitExceededException;

class Github {

  private $debug;

  private $githubClientID;

  private $githubClientSecret;

  private $githubCallbackUrl;

  public function __construct() {
    $config = new Config("../app/config/app.php");

    if ($config->get("GITHUB_CLIENT_ID") == null) {
      throw new MissingCredentialsException("GITHUB_CLIENT_ID needs to be set!");
    }
    if ($config->get("GITHUB_CLIENT_SECRET") == null) {
      throw new MissingCredentialsException("GITHUB_CLIENT_SECRET needs to be set!");
    }
    if ($config->get("GITHUB_CALLBACK_URL") == null) {
      throw new MissingCredentialsException("GITHUB_CALLBACK_URL needs to be set!");
    }

    $this->githubClientID = $config->get("GITHUB_CLIENT_ID");
    $this->githubClientSecret = $config->get("GITHUB_CLIENT_SECRET");
    $this->githubCallbackUrl = $config->get("GITHUB_CALLBACK_URL");
    $this->debug = $config->get("DEBUG");
  }

  public function authorize() {
    $url = "https://github.com/login/oauth/authorize?client_id="
      . $this->githubClientID  . "&redirect_uri=" . $this->githubCallbackUrl;

    return $url;
  }

  public function getAllUsers($page = null, $sortBy = "followers") {
    # TODO: Better urls…
    $url = "https://api.github.com/search/users?q={$sortBy}";
    if ($sortBy === "repos") {
      $url .= urlencode(":>=228");
    }
    else {
      $url .= urlencode(":>=312");
    }
    $url .= "&order=asc&client_id={$this->githubClientID}&client_secret={$this->githubClientSecret}";
    $url .= "&per_page=100";
    if ($page) {
      $url .= "&page=" . $page;
    }

    $request = new Request($url);
    $response = $request->performRequest();

    $pages = $this->getPaging($response);

    $body = json_decode($response->getBody());

    $this->rateLimitExceeded($response);

    return array(
      "body" => $body,
      "pages" => $pages
    );
  }

  public function getSingleUser($username) {
    $url = "https://api.github.com/users/{$username}?client_id={$this->githubClientID}&client_secret={$this->githubClientSecret}";
    $request = new Request($url);
    $response = $request->performRequest(); 

    $this->rateLimitExceeded($response);

    return json_decode($response->getBody());
  }

  public function getUsersRepos($username) {
    $url = "https://api.github.com/users/" . $username . "/repos";
    $request = new Request($url);
    $response = $request->performRequest();

    $this->rateLimitExceeded($response);

    return json_decode($response->getBody());
  }

  public function getUserFollowers($username) {
    $url = "https://api.github.com/users/" . $username . "/followers";
    $request = new Request($url);
    $response = $request->performRequest();

    $this->rateLimitExceeded($response);

    return json_decode($response->getBody());
  }

  /**
   * @param Response $response 
   * @return Array
   */
  private function getPaging(Response $response) {
    $links = $response->getHeader("Link");

    $pagination = array();
    $links = explode(",", $links);

    foreach ($links as $link) {
      preg_match('/<(.*)>; rel="(.*)"/i', trim($link, ","), $match);

      $pagination[$match[2]] = $match[1];
    }

    return $pagination;
  }

  /**
   * @param Response $response 
   * @throws RateLimitExceededException
   */
  private function rateLimitExceeded(Response $response) {
    $numberOfCalls = $response->getHeader("X-RateLimit-Limit");
    $remainingCalls = $response->getHeader("X-RateLimit-Remaining");

    if ($this->debug === true) {
      $timeRemaining = $response->getHeader("X-RateLimit-Reset");
      $date = new \DateTime();
      $date->setTimestamp($timeRemaining);
      print("<pre>Number of calls: " . $numberOfCalls . "</pre>");
      print("<pre>Number of calls left: " . $remainingCalls . "</pre>");
      print("<pre>New set of calls available at: " . $date->format('Y-m-d H:i:s') . "</pre>");
    }

    if ($numberOfCalls !== null && $remainingCalls !== null
      && $remainingCalls == 0) {
      throw new RateLimitExceededException("Github does not allow more requests for you! Limit is: " . $numberOfCalls);
    }
  }

}