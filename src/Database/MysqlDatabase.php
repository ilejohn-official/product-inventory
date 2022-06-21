<?php

namespace App\Database;

use App\Interface\DatabaseInterface;
use \PDO as Pdo;
class MysqlDatabase implements DatabaseInterface
{

    protected $pdo = null;
    protected $host;
    protected string $dbName;
    protected string $username;
    protected string $password;
    protected string $table;
    protected $config = [];

    public function __construct()
    {
        $config = require __DIR__."../../../config/db.php";

        $this->host = $config['host'];
        $this->dbName = $config['database'];
        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->config = $config;
    }

    public function connect() : Pdo 
    {
        $opt  = array(
            Pdo::ATTR_ERRMODE            => Pdo::ERRMODE_EXCEPTION,
            Pdo::ATTR_DEFAULT_FETCH_MODE => Pdo::FETCH_OBJ,
            Pdo::ATTR_EMULATE_PREPARES   => FALSE,
        );

        if (!isset($this->pdo)) {
         $this->pdo = new Pdo('mysql:host='.$this->host.';dbname='.$this->dbName.';charset='.$this->config['charset'],
           $this->username, 
           $this->password,
           $opt
         );
        }

        return $this->pdo;
    }

    public function setTable(string $table) : void
    {
        $this->table = $table;
    }

    public function query(string $query, array $params = null) 
    {
        $statement = $this->connect()->prepare($query);
        $statement->execute($params);
        if(explode(' ', $query)[0] == 'SELECT'){
          $data = $statement->fetchAll();
          return $data;
        }
    }

    public function getAll()
    {
        return $this->query("SELECT * FROM $this->table");
    }

    public function store(array $param)
    {
        return $this->query("INSERT INTO $this->table ".$this->getArrayKeys($param)." VALUES ".$this->arrayToWildCard($params), $params);
    }

    public function delete(array $ids)
    {
        $ids = array_map([$this->connect, 'quote'], $ids);

        return $this->query("DELETE FROM $this->table WHERE id IN ".$this->arrayToWildCard($ids), $ids);
    }

    private function arrayToWildCard(array $param) : string
    {
        return "(".str_repeat('?,', count($param) - 1)."?)";
    }

    private function getArrayKeys(array $param): string
    {
        return "(".implode(',', array_keys($param)).")";
    }

}
