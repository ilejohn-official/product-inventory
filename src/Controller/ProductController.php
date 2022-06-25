<?php

namespace App\Controller;

use App\Interface\RequestInterface;
use App\Interface\ProductServiceInterface;
use App\Request\CreateProductRequest;
use App\Request\DeleteProductRequest;

class ProductController 
{
  

  public function __construct(private ProductServiceInterface $productService) 
  {
    
  }

  public function show()
  {
    $products = $this->productService->getAllProducts();
    
    require_once  __DIR__.'../../../view/products.view.html';
  }

  public function showStoreForm()
  {
    $add = 'This is where you add products to db';
    require_once  __DIR__.'../../../view/add_products.view.php';
  }

  public function store(CreateProductRequest $request)
  {
    $request->validate();

    $body = $request->getValidated();

    $param = [
      'sku' => $body['sku'],
      'name' => $body['name'],
      'price' => $body['price'],
      'attribute' => json_encode([
        'key' => $body['attribute_key'],
        'value' => $body['attribute_value'],
        'unit' => $body['attribute_unit']
      ])
    ];

    try {
      $output = $this->productService->storeProduct($param);
    } catch (\Throwable $th) {
      header('Content-Type: application/json; charset=utf-8', TRUE, 406);
      echo json_encode([
        'status_code' => 406,
        'status' => false,
        'message' => strpos($th->getMessage(), 'Duplicate entry') ? 'Sku must be unique. choose another' : 'Failed',
        'data' => null
      ]);
      die;
    } 

    header('Content-Type: application/json; charset=utf-8', TRUE, 200);
    echo json_encode([
      'status_code' => 200,
      'status' => true,
      'message' => 'Successful',
      'data' => $output
    ]);
  }

  public function delete(DeleteProductRequest $request)
  {
    $request->validate();

    $body = $request->getValidated();

    $this->productService->deleteProducts($body['ids']);

    header('Content-Type: application/json; charset=utf-8', TRUE, 200);
    echo json_encode([
      'status_code' => 200,
      'status' => true,
      'message' => 'Successful',
      'data' => null
    ]);
  }
}
