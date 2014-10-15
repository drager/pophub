<?php

namespace Kagu\Http;

interface HttpRequestAdapterInterface {

  function setUrl($url);

  function getUrl();

  function get();

  function post(array $data);

  function put(array $data);

  function delete();
}