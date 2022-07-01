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
        $this->product->sku = $params['sku'];
        $this->product->name = $params['name'];
        $this->product->price = $params['price'];

        $this->product->attribute = json_encode([
            'key' => $params['productType_key'],
            'value' => implode('x', $params['attributeValue']),
            'unit' => $params['productType_unit']
        ]);
          
        return $this->product->store();
    }

    public function deleteProducts(array $params)
    {
        return $this->product->delete($params['ids']);
    }
}
