<?php

namespace App\Http\Controllers\Api\Seller;

use App\Models\Upload;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
        /**
     * Store a newly created resource in storage.
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // TODO: Implement update() method.
    }

    /**
     * Remove the specified resource from storage.
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
