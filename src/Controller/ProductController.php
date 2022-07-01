<?php

namespace App\Controller;

use App\Interface\RequestInterface;
use App\Interface\ProductServiceInterface;
use App\Request\CreateProductRequest;
use App\Request\DeleteProductRequest;
use App\Trait\Response;

class ProductController
{
    use Response;

    public function __construct(private ProductServiceInterface $productService)
    {
    }

    public function show()
    {
        $this->view('products.view.html');
    }

    public function getProducts()
    {
        $products = $this->productService->getAllProducts();

        $this->json([
          'status_code' => 200,
          'status' => true,
          'message' => 'Successful',
          'data' => $products
        ], JSON_PRESERVE_ZERO_FRACTION|JSON_FORCE_OBJECT);
    }

    public function showStoreForm()
    {
        $this->view('add_products.view.html');
    }

    public function store(CreateProductRequest $request)
    {
        $request->validate();

        $output = $this->productService->storeProduct($request->getValidated());

        $this->json([
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

        $this->json([
          'status_code' => 200,
          'status' => true,
          'message' => 'Successful',
          'data' => null
        ]);
    }
}
