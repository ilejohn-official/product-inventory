<?php

namespace App\Controller;

use App\Interface\RequestInterface;
use App\Interface\ProductServiceInterface;

class ProductController 
{
  

  public function __construct(private ProductServiceInterface $productService) 
  {
    
  }

  public function show()
  {
    $products = $this->productService->getAllProducts();
    
    require_once  __DIR__.'../../../view/products.view.php';
  }

  public function showStoreForm()
  {
    $add = 'This is where you add products to db';
    require_once  __DIR__.'../../../view/add_products.view.php';
  }

  public function store(RequestInterface $request)
  {

    $body = $request->getBody();

    $param = [
      'sku' => $body['sku'],
      'name' => $body['name'],
      'price' => $body['price'],
      'attribute' => json_encode([
        'key' => $body['attribute_key'],
        'value' => $body['attribute_value']
      ])
    ];

    $output = $this->productService->storeProduct($param);

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
      'status' => 200,
      'message' => 'Successful',
      'data' => $request->getBody()
    ]);
  }
}
