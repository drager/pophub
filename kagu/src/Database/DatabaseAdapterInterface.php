<?php

namespace Kagu\Database;

interface DatabaseAdapterInterface {

  function connect();

  function select($table, $fields = "*", $where = null, array $params = null, $order = null, $limit = null);

  function insert($table, array $data);
}