<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;

/**
 * @group Products
 *
 * API for view all orders from user
 */
class ProductController extends Controller
{
    /**
     * Show all products available.
     */
    public function index()
    {
        return $this->sendRes([
            'products' => Product::all()
        ]);
    }

    /**
     * Show spesific product by id
     * @urlParam id required The id product. Example: 9c5eb8ed-0825-4b8f-a7c7-f2434b9a0677
     */
    public function show(string $id)
    {
        try {
            // make a show products by id
            $product = Product::findOrFail($id);

            return $this->sendRes([
                'product' => $product
            ]);

        } catch (\Exception $e) {
            return $this->sendFailRes($e, 401);
        }
    }
}
