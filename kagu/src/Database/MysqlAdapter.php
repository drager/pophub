<?php

namespace Kagu\Database;

use Kagu\Config\Config;

class MysqlAdapter implements DatabaseAdapterInterface {

  protected $config;

  protected $dbConnection;

  /**
   * @param Config $config
   * @return void
   */
  public function __construct(Config $config) {
    $this->config = $config;
  }

  /**
   * Creates a database conneciton.
   * @return PDO object
   */
  public function connect() {

    if ($this->dbConnection === null) {
      $this->dbConnection = new \PDO($this->config->get("DB_CONNECTION"), $this->config->get("DB_USER"),
        $this->config->get("DB_PASSWORD"));
    }

    $this->dbConnection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    return $this->dbConnection;
  }

  /**
   * Select data from a given table with some parameters
   * @param String $table
   * @param String $fields
   * @param String $where
   * @param Array $params
   * @param String $order
   * @param String $limit
   * @return Array
   */
  public function select($table, $fields = "*", $where = null, array $params = null, $order = null, $limit = null) {
    $db = $this->dbConnection;

    $sql = "SELECT " . $fields . " FROM " . $table . ($where ? " WHERE " . $where . " = ?" : "") .
      ($order ? " ORDER BY " . $order . " DESC" : "");

    var_dump($sql);

    $query = $db->prepare($sql);
    $params ? $query->execute($params) : $query->execute();

    $result = $query->fetchAll(\PDO::FETCH_ASSOC);

    var_dump($params);

    if ($result) {
      return $result;
    }

    return null;
  }

  /**
   * Insert data into a given table with some data
   * @param String $table
   * @param Array $data
   */
  public function insert($table, array $data) {
    $db = $this->dbConnection;

    $fields = join(", ", array_keys($data));
    $values = join(", ", array_values($data));
    $params = explode(", ", $values);

    //var_dump($params);

    $count = count($data);

    //var_dump($count);

    //var_dump($fields);

    $sql = "INSERT INTO " . $table . " (" . $fields . ") VALUES (";
    for ($i = 0; $i < $count; $i++) {
      $i === $count-1 ? $sql .= "?" : $sql .= "?, ";
    }
    $sql .= ")";

    //var_dump($sql);

    $query = $db->prepare($sql);
    $query->execute($params);
  }
}