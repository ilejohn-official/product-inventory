<?php

namespace App\Controller;

use App\Interface\RequestInterface;

class ProductController 
{
  public function __construct() 
  {

  }

  public static function show()
  {
    require_once  __DIR__.'../../../view/products.view.php';
  }

  public static function showStoreForm()
  {
    $add = 'This is where you add products to db';
    require_once  __DIR__.'../../../view/add_products.view.php';
  }

  public static function store(RequestInterface $request)
  {

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
      'status' => 200,
      'message' => 'Successful',
      'data' => $request->getBody()
    ]);
  }
}
