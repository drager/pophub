<?php

namespace PopHub\Model;

use Kagu\Http\Request;
use Kagu\Http\Response;
use Kagu\Config\Config;

class Github {

  private $baseUrl = "https://api.github.com";

  private $oauthUrl = "https://github.com/login/oauth/authorize";

  private $config;

  public function __construct(Config $config) {
    $this->config = $config;
  }

  public function authorize() {
    $url = $this->oauthUrl . "?client_id=" . $this->config->get("GITHUB_CLIENT_ID")  .
      "&redirect_uri=" . $this->config->get("GITHUB_CALLBACK_URL");

    $request = new Request($url);

    return $url;
  }

  public function postAccessToken($code) {
    $url = "https://github.com/login/oauth/access_token?client_id=" . $this->config->get("GITHUB_CLIENT_ID")  .
      "&client_secret=" . $this->config->get("GITHUB_CLIENT_SECRET") . "&code=" . rawurldecode($code);

    $request = new Request($url);

    $response = $request->post(array("code" => $code));

    $token = $response->getBody();

    // Check if we have a valid token
    if (preg_match("#^access_token=#", $token) === 0) {
      throw new \Exception("Code invalid!");
    }

    return $token;
  }

  public function getLoggedInUser($accessToken) {
    $url = $this->baseUrl . "/user?" . $accessToken ."&client_id="
      . $this->config->get("GITHUB_CLIENT_ID") . "&client_secret=" . $this->config->get("GITHUB_CLIENT_SECRET");

    $request = new Request($url);

    $response = $request->get();

    $body = json_decode($response->getBody());

    return $body;
  }

  public function getAllUsers($page = null, $sortBy = "followers", $language = null) {
    # TODO: Better urls…
    #https://api.github.com/search/users?q=language:php&followers:%3E=312
    if ($language !== null) {
      $url = $this->baseUrl . "/search/users?q=language";
      $url .= rawurldecode(":".$language);
      $url .= "&{$sortBy}";
    } else {
      $url = $this->baseUrl . "/search/users?q={$sortBy}";
    }

    if ($sortBy === "repos") {
      $url .= rawurldecode(":>=228");
    } else {
      $url .= rawurldecode(":>=312");
    }

    $url .= "&order=asc&client_id=" . $this->config->get("GITHUB_CLIENT_ID") .
      "&client_secret=" . $this->config->get("GITHUB_CLIENT_SECRET");
    $url .= "&per_page=100";
    if ($page) {
      $url .= "&page=" . $page;
    }

    $request = new Request($url);

    $response = $request->get();

    $pages = $this->getPaging($response);

    $body = json_decode($response->getBody());

    return array(
      "body" => $body,
      "pages" => $pages
    );
  }

  public function getSingleUser($username) {
    $url = $this->baseUrl . "/users/" . $username . "?client_id="
      . $this->config->get("GITHUB_CLIENT_ID") . "&client_secret=" . $this->config->get("GITHUB_CLIENT_SECRET");

    $request = new Request($url);

    $response = $request->get();

    // $this->rateLimitExceeded($response);

    return json_decode($response->getBody());
  }

  public function getUserRepos($username) {
    $url = $this->baseUrl . "/users/" . $username . "/repos?client_id="
      . $this->config->get("GITHUB_CLIENT_ID") . "&client_secret=" . $this->config->get("GITHUB_CLIENT_SECRET");

    $request = new Request($url);

    $response = $request->get();

    // $this->rateLimitExceeded($response);

    return json_decode($response->getBody());
  }

  public function getUserFollowers($username) {
    $url = $this->baseUrl . "/users/" . $username . "/followers?client_id="
      . $this->config->get("GITHUB_CLIENT_ID") . "&client_secret=" . $this->config->get("GITHUB_CLIENT_SECRET");

    $request = new Request($url);

    $response = $request->get();

    // $this->rateLimitExceeded($response);

    return json_decode($response->getBody());
  }

  public function getUserActivity($username) {
    $url = $this->baseUrl . "/users/" . $username . "/events?client_id="
      . $this->config->get("GITHUB_CLIENT_ID") . "&client_secret=" . $this->config->get("GITHUB_CLIENT_SECRET");

    $request = new Request($url);

    $response = $request->get();

    // $this->rateLimitExceeded($response);

    return json_decode($response->getBody());
  }

  /**
   * @param Response $response
   * @return Array
   */
  private function getPaging(Response $response) {
    $links = $response->getHeader("Link");

    if ($links === null) {
      return;
    }

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

    $timeRemaining = $response->getHeader("X-RateLimit-Reset");
    $date = new \DateTime();
    $date->setTimestamp($timeRemaining);
    print("<pre>Number of calls: " . $numberOfCalls . "</pre>");
    print("<pre>Number of calls left: " . $remainingCalls . "</pre>");
    print("<pre>New set of calls available at: " . $date->format('Y-m-d H:i:s') . "</pre>");

    if ($numberOfCalls !== null && $remainingCalls !== null
      && $remainingCalls == 0) {
      throw new RateLimitExceededException("Github does not allow more requests for you! Limit is: " . $numberOfCalls);
    }
  }
}