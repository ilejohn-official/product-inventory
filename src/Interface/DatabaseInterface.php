<?php

namespace App\Interface;

interface DatabaseInterface
{
    public function connect();

    public function setTable(string $table) : void;

    public function query(string $query, array $params);

    public function getAll() : array;

    public function exists(string $key, mixed $value) : int;

    public function store(array $params);

    public function delete(array $params);
}
