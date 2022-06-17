<?php

namespace App\Controller;

class ProductController 
{
  public function __construct() 
  {

  }

  public static function show()
  {
    require_once  __DIR__.'../../../view/products.view.php';
  }
}
