<?php

namespace App\Model;

use App\Interface\DatabaseInterface;

abstract class Model
{
    /**
     * model data to save
     * @var array $data
     */
    protected $data = [];

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

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
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

    public function store()
    {
        return $this->db->store($this->data);
    }

    public function delete(array $params)
    {
        return $this->db->delete($params);
    }
}
