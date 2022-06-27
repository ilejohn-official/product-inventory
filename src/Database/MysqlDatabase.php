<?php

namespace App\Database;

use App\Interface\DatabaseInterface;
use \PDO as Pdo;
class MysqlDatabase implements DatabaseInterface
{

    protected Pdo $pdo;
    protected string $table;

    public function __construct(
        private string $host,
        private string $dbName,
        private string $username,
        private string $password,
        private string $charset
    )
    {
    }

    public function connect() : Pdo 
    {
        $opt  = array(
            Pdo::ATTR_ERRMODE            => Pdo::ERRMODE_EXCEPTION,
            Pdo::ATTR_DEFAULT_FETCH_MODE => Pdo::FETCH_OBJ,
            Pdo::ATTR_EMULATE_PREPARES   => FALSE,
        );

        if (!isset($this->pdo)) {
         $this->pdo = new Pdo('mysql:host='.$this->host.';dbname='.$this->dbName.';charset='.$this->charset,
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

    public function exists(string $key, mixed $value)
    {
       $result = $this->query("SELECT COUNT($key) as count FROM $this->table WHERE $key = ?", [$value]);

       return $result[0]->count;
    }

    public function store(array $param)
    {
        return $this->query(
            "INSERT INTO $this->table ".$this->getArrayKeys($param)." VALUES ".$this->arrayToWildCard($param),
             $this->arrayValueParams($param)
        );
    }

    public function delete(array $ids)
    {
        $ids = array_map(fn($value): int => (int)$value, $ids);
        
        return $this->query(
            "DELETE FROM $this->table WHERE id IN ".$this->arrayToWildCard($ids),
            $this->arrayValueParams($ids)
        );
    }

    private function arrayToWildCard(array $param) : string
    {
        return "(".str_repeat('?,', count($param) - 1)."?)";
    }

    private function getArrayKeys(array $param): string
    {
        return "(".implode(',', array_keys($param)).")";
    }

    private function arrayValueParams(array $param) : array
    {
        return array_values($param);
    }

}
