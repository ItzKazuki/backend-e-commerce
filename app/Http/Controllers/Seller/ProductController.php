<?php

namespace App\Http\Controllers\Seller;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Upload;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('sellers.products.index', [
            'products' => Product::where('seller_id', auth()->user()->id)->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sellers.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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

        return redirect()->route('product.index')->with('Success', 'Success add product to database');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
        return redirect()->to(config('app.frontend_url'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('sellers.products.edit', [
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $productData = $request->validate([
            'product_name' => 'required',
            'product_desc' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'brand' => 'required',
            // 'product_image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if(isset($request->product_image)){
            $imagePath = $request->file('product_image')->store('products');
            $imageUrl = Storage::url($imagePath);

            // set imageUrl to Upload
            $uplaodDetail = Upload::create([
                'image' => $imageUrl,
                'user_id' => $request->user()->id
            ]);
        }

        $product->product_name = $productData['product_name'];
        $product->product_desc = $productData['product_desc'];
        $product->price = $productData['price'];
        $product->stock = $productData['stock'];
        $product->brand = $productData['brand'];
        if(isset($request->product_image)){
            $product->upload_id = $uplaodDetail->id;
        }
        $product->save();

        return redirect()->route('product.index')->with('Success', 'Success update product to database');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('product.index')->with('Success', 'Success delete product to database');
    }
}
