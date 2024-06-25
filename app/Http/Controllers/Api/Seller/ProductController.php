<?php

namespace App\Http\Controllers\Api\Seller;

use App\Models\Upload;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

/**
 * @group Seller
 *
 * API for create, view, all product and order.
 * @authenticated
 */

class ProductController extends Controller
{
    /**
     * Create new Product
     *
     * seller create new product with image
     * @bodyParam product_name string for display product name. Example: Bengbeng
     * @bodyParam product_desc string for display product description. Example: Bengbeng is a cruchy snack with chocolate
     * @bodyParam price int for display product price. Example: 2000
     * @bodyParam stock int for display product stock. Example: 77
     * @bodyParam brand string for display product brand. Example: Mayora
     * @bodyParam product_image file for display product image.
     */
    public function store(Request $request)
    {
        try {
            $productData = $request->validate([
                'product_name' => 'required',
                'product_desc' => 'required',
                'price' => 'required',
                'stock' => 'required',
                'brand' => 'required',
                'product_image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            $imagePath = $request->file('product_image')->store('products');

            $imageUrl = Storage::url($imagePath);

            // set imageUrl to Upload
            $uplaodDetail = Upload::create([
                'image' => $imageUrl,
                'user_id' => $request->user()->id
            ]);

            // set productData to Product
            $product = Product::create([
                'product_name' => $productData['product_name'],
                'product_desc' => $productData['product_desc'],
                'price' => $productData['price'],
                'stock' => $productData['stock'],
                'brand' => $productData['brand'],
                'seller_id' => $request->user()->id,
                'upload_id' => $uplaodDetail->id
            ]);

            return $this->sendRes([
                'message' => 'Product created successfully',
                'product' => $product
            ]);


        } catch (\Exception $e) {
            return $this->sendFailRes($e, 400);
        }
    }

    /**
     * Update Product
     *
     * update product with spesific id
     * @urlParam id required The id product. Example: 9c5e925b-9d7e-4d5f-9502-151522a72683
     * 
     */
    public function update(Request $request, string $id)
    {
        // TODO: Implement update() method.
    }

    /**
     * Delete Product
     *
     * seller can delete product with spesific id
     * @urlParam id required The id product. Example: aaa
     */
    public function destroy(string $id)
    {
        try {
            // make a delete products by id
            $product = Product::findOrFail($id);
            $product->delete();

            return $this->sendRes([
                'message' => 'Product deleted successfully'
            ]);

        } catch (\Exception $e) {
            return $this->sendFailRes($e, 401);
        }
    }
}
