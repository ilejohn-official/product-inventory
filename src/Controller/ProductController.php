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
        require_once  __DIR__.'../../../view/products.view.html';
    }

    public function getProducts()
    {
        $products = $this->productService->getAllProducts();

        header('Content-Type: application/json; charset=utf-8', true, 200);
        echo json_encode([
          'status_code' => 200,
          'status' => true,
          'message' => 'Successful',
          'data' => $products
        ], JSON_PRESERVE_ZERO_FRACTION|JSON_FORCE_OBJECT);
    }

    public function showStoreForm()
    {
        require_once  __DIR__.'../../../view/add_products.view.html';
    }

    public function store(CreateProductRequest $request)
    {
        $request->validate();

        $output = $this->productService->storeProduct($request->getValidated());

        header('Content-Type: application/json; charset=utf-8', true, 200);
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

        $this->productService->deleteProducts($request->getValidated());

        header('Content-Type: application/json; charset=utf-8', true, 200);
        echo json_encode([
          'status_code' => 200,
          'status' => true,
          'message' => 'Successful',
          'data' => null
        ]);
    }
}
