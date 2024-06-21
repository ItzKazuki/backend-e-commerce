<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->sendRes([
            'products' => Product::with(['upload', 'seller'])->get()
        ]);
    }

    /**
     * Display the specified resource.
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
