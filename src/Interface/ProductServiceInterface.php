<?php

namespace App\Interface;

interface ProductServiceInterface
{
    public function getAllProducts();

    public function storeProduct(array $params);
}
