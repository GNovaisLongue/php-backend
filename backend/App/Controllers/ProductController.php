<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Models\Product;

class ProductController
{
    private Product $products;

    public function __construct(?Product $products = null)
    {
        $this->products = $products ?? new Product();
    }

    public function index(): Response
    {
        return Response::json($this->products->getAllProducts());
    }

    public function show(string $sku): Response
    {
        $product = $this->products->getProductById($sku);
        if ($product === null) {
            return Response::error('Product not found', 404);
        }

        return Response::json($product);
    }

    public function store(Request $request): Response
    {
        $sku = $this->products->insertProduct($request->getBody());

        return Response::json(['sku' => $sku], 201);
    }

    public function destroy(string $sku): Response
    {
        $deleted = $this->products->deleteProduct($sku);
        if ($deleted === 0) {
            return Response::error('Product not found', 404);
        }

        return Response::json(['deleted' => $deleted]);
    }

    public function destroyMany(Request $request): Response
    {
        $body = $request->getBody();
        // Accept { "skus": [...] } or a bare JSON array.
        $skus = $body['skus'] ?? $body;
        $deleted = $this->products->deleteProducts(is_array($skus) ? $skus : []);

        return Response::json(['deleted' => $deleted]);
    }
}
