<?php

namespace App\Interface;

interface ProductServiceInterface
{
    public function getAllProducts() : array;

    public function storeProduct(array $params);
}
