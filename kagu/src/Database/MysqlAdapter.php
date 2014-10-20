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

    $query = $db->prepare($sql);
    $params ? $query->execute($params) : $query->execute();

    $result = $query->fetchAll(\PDO::FETCH_ASSOC);

    if ($result) {
      return $result;
    }

    return null;
  }
}