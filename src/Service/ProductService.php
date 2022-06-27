<?php

namespace App\Service;

use App\Model\Model;
use App\Interface\ProductServiceInterface;

class ProductService implements ProductServiceInterface
{
    public function __construct(private Model $product)
    {
    }

    public function getAllProducts() : array
    {
        $products = $this->product->get();

        return array_map(
            function ($item) {
                $item->attribute = json_decode($item->attribute, true);
                return $item;
            }, 
            $products
        );
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
