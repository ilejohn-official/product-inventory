<?php

namespace App\Model;

use App\Interface\DatabaseInterface;

abstract class Model
{
    /**
     * @param DatabaseInterface $db
     *
     * @return void
     */
    public function __construct(private DatabaseInterface $db)
    {
        $this->db->setTable(self::getTableName());
    }

    /**
     * @return string
     */
    final public static function getTableName() : string
    {
        return static::$table;
    }

    /**
     * @return array
     */
    public function get() : array
    {
        return $this->db->getAll();
    }

    /**
     * @return int
     */
    public function exists(string $key, mixed $value) : int
    {
        return $this->exists($key, $value);
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
