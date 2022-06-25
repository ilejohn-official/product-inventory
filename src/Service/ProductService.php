<?php

namespace App\Service;

use App\Model\Model;
use App\Interface\ProductServiceInterface;

class ProductService implements ProductServiceInterface
{
    public function __construct(private Model $product)
    {
    }

    public function getAllProducts() 
    {
        return $this->product->get();
    }

    public function storeProduct(array $params)
    {
        return $this->product->store($params);
    }

    public function deleteProducts(array $ids)
    {
        return $this->product->delete($ids);
    }
}
