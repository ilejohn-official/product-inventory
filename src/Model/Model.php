<?php

namespace App\Model;

use App\Interface\DatabaseInterface;

abstract class Model
{
    public function __construct(private DatabaseInterface $db)
    {
        $this->db->setTable(self::getTableName());
    }

    public static final function getTableName()
    {
        return static::$table;
    }

    public function get() 
    {
        return $this->db->getAll();
    }

    public function store(array $params)
    {
        return $this->db->store($params);
    }

    public function delete(array $ids)
    {
        return $this->db->delete($ids);
    }
}