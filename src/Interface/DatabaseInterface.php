<?php

namespace App\Interface;

interface DatabaseInterface
{
    public function connect();

    public function setTable(string $table);

    public function query(string $query, array $params);

    public function getAll();

    public function store(array $params);

    public function delete(array $params);
}
