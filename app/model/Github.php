<?php

namespace PopHub\Model;

use Kagu\Http\Request;
use Kagu\Http\Response;
use Kagu\Config\Config;
use Kagu\Exception\MissingCredentialsException;

class Github {

  private $debug;

  private $githubClientID;

  private $githubClientSecret;

  private $githubCallbackUrl;

  public function __construct($githubClientID, $githubClientSecret, $githubCallbackUrl) {
    $this->githubClientID = $githubClientID;
    $this->githubClientSecret = $githubClientSecret;
    $this->githubCallbackUrl = $githubCallbackUrl;
    // $this->debug = $config->get("DEBUG");

    if ($this->githubClientID === null) {
      throw new \InvalidArgumentException("GITHUB_CLIENT_ID needs to be set!");
    }

    if ($this->githubClientSecret === null) {
      throw new \InvalidArgumentException("GITHUB_CLIENT_SECRET needs to be set!");
    }

    if ($this->githubCallbackUrl === null) {
      throw new \InvalidArgumentException("GITHUB_CALLBACK_URL needs to be set!");
    }
  }

  public function authorize() {
    $url = "https://github.com/login/oauth/authorize?client_id="
      . $this->githubClientID  . "&redirect_uri=" . $this->githubCallbackUrl;

    $request = new Request($url);

    return $url;
  }

  public function postAccessToken($code) {
    $url = "https://github.com/login/oauth/access_token?client_id="
      . $this->githubClientID  . "&client_secret=" . $this->githubClientSecret
      . "&code=" . $code;

    // var_dump($code);


    $request = new Request($url);
    $response = $request->performRequest("POST", array("code" => $code));

    $token = $response->getBody();

    // Check if we have a valid token
    if (preg_match("#^access_token=#", $token) === 0) {
      throw new \Exception("Code invalid!");
    }

    return $token;
  }

  public function getLoggedInUser($accessToken) {
    $url = "https://api.github.com/user?{$accessToken}&client_id={$this->githubClientID}&client_secret={$this->githubClientSecret}";

    $request = new Request($url);
    $response = $request->performRequest();

    $body = json_decode($response->getBody());

    var_dump($url);

    return $body;
  }

  public function getAllUsers($page = null, $sortBy = "followers", $language = null) {
    # TODO: Better urls…
    #https://api.github.com/search/users?q=language:php&followers:%3E=312
    if ($language !== null) {
      $url = "https://api.github.com/search/users?q=language";
      $url .= urlencode(":".$language);
      $url .= "&{$sortBy}";
    } else {
      $url = "https://api.github.com/search/users?q={$sortBy}";
    }

    if ($sortBy === "repos") {
      $url .= urlencode(":>=228");
    } else {
      $url .= urlencode(":>=312");
    }

    $url .= "&order=asc&client_id={$this->githubClientID}&client_secret={$this->githubClientSecret}";
    $url .= "&per_page=100";
    if ($page) {
      $url .= "&page=" . $page;
    }

    // var_dump($url);

    $request = new Request($url);
    $response = $request->performRequest();

    // var_dump($response->getHeaders());

    $pages = $this->getPaging($response);

    $body = json_decode($response->getBody());

    // var_dump($body);

    // $this->rateLimitExceeded($response);

    return array(
      "body" => $body,
      "pages" => $pages
    );
  }

  public function getSingleUser($username) {
    $url = "https://api.github.com/users/{$username}?client_id={$this->githubClientID}&client_secret={$this->githubClientSecret}";
    $request = new Request($url);
    $response = $request->performRequest(); 

    // $this->rateLimitExceeded($response);

    return json_decode($response->getBody());
  }

  public function getUsersRepos($username) {
    $url = "https://api.github.com/users/" . $username . "/repos?client_id={$this->githubClientID}&client_secret={$this->githubClientSecret}";
    $request = new Request($url);
    $response = $request->performRequest();

    // $this->rateLimitExceeded($response);

    return json_decode($response->getBody());
  }

  public function getUserFollowers($username) {
    $url = "https://api.github.com/users/" . $username . "/followers?client_id={$this->githubClientID}&client_secret={$this->githubClientSecret}";
    $request = new Request($url);
    $response = $request->performRequest();

    // $this->rateLimitExceeded($response);

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
  // private function rateLimitExceeded(Response $response) {
  //   $numberOfCalls = $response->getHeader("X-RateLimit-Limit");
  //   $remainingCalls = $response->getHeader("X-RateLimit-Remaining");

  //   if ($this->debug === true) {
  //     $timeRemaining = $response->getHeader("X-RateLimit-Reset");
  //     $date = new \DateTime();
  //     $date->setTimestamp($timeRemaining);
  //     print("<pre>Number of calls: " . $numberOfCalls . "</pre>");
  //     print("<pre>Number of calls left: " . $remainingCalls . "</pre>");
  //     print("<pre>New set of calls available at: " . $date->format('Y-m-d H:i:s') . "</pre>");
  //   }

  //   if ($numberOfCalls !== null && $remainingCalls !== null
  //     && $remainingCalls == 0) {
  //     throw new RateLimitExceededException("Github does not allow more requests for you! Limit is: " . $numberOfCalls);
  //   }
  // }

}