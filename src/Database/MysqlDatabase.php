<?php

namespace App\Database;

use App\Interface\DatabaseInterface;



class MysqlDatabase implements DatabaseInterface
{

    protected $pdo = null;
    protected $host;
    protected $dbName;
    protected $username;
    protected $password;
    protected $config = [];

    public function __construct(private string $table)
    {
        $config = require __DIR__."../../../config/db.php";

        $this->host = $config['host'];
        $this->dbName = $config['database'];
        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->config = $config;
    }

    public function connect() : \PDO 
    {
        $opt  = array(
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
            \PDO::ATTR_EMULATE_PREPARES   => FALSE,
        );

        if (!isset($this->pdo)) {
         $this->pdo = new \PDO('mysql:host='.$this->host.';dbname='.$this->dbName.';charset='.$this->config['charset'],
           $this->username, 
           $this->password,
           $opt
         );
        }

        return $this->pdo;
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
