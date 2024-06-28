<?php

namespace App\Http\Controllers\Api\Seller;

use Exception;
use App\Models\Upload;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Notifications\Product\ProductCreated;

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
                'product_name' => 'required|string|max:100',
                'product_desc' => 'required|string',
                'price' => 'required|integer',
                'stock' => 'required|integer',
                'brand' => 'required|string|max:100',
                'product_image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            $imagePath = $request->file('product_image')->store('assets/products');

            $imageUrl = Storage::url($imagePath);

            // set imageUrl to Upload
            $uplaodDetail = Upload::create([
                'image' => $imageUrl,
                'user_id' => $request->user()->id
            ]);

            $productData['seller_id'] = $request->user()->id;
            $productData['upload_id'] = $uplaodDetail->id;

            // set productData to Product
            $product = Product::create($productData);

            $request->user()->notify(new ProductCreated($request->user(), $product));

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
     * @bodyParam product_name string for display product name. Example: Bengbeng
     * @bodyParam product_desc string for display product description. Example: Bengbeng is a cruchy snack with chocolate
     * @bodyParam price int for display product price. Example: 2000
     * @bodyParam stock int for display product stock. Example: 77
     * @bodyParam brand string for display product brand. Example: Mayora
     * @bodyParam product_image file for display product image.
     */
    public function update(Request $request, string $id)
    {
        try {
            $product = Product::find($id);

            if(!$product) throw new Exception('Product not found', 404);

            $productData = $request->validate([
                'product_name' => 'string|max:100',
                'product_desc' => 'string',
                'price' => 'integer',
                'stock' => 'integer',
                'brand' => 'string|max:100',
                'product_image' => 'image|mimes:jpeg,png,jpg|max:2048'
            ]);

            if(isset($request->product_image)){
                $imagePath = $request->file('product_image')->store('assets/products');
                $imageUrl = Storage::url($imagePath);

                // set imageUrl to Upload
                $uplaodDetail = Upload::create([
                    'image' => $imageUrl,
                    'user_id' => $request->user()->id
                ]);
            }

            if(isset($request->product_image)){
                $productData['upload_id'] = $uplaodDetail->id;
            }

            $product->update($productData);

            return $this->sendRes([
                'message' => 'Product updated successfully',
                'product' => $product
            ]);

        } catch (Exception $e) {
            return $this->sendFailRes($e);
        }
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
