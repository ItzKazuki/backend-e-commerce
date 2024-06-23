<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Models\Product;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->sendRes([
            'orders' => Order::where('customer_id', auth()->user()->id)->get()
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $order = Order::findOrFail($id);

            return $this->sendRes([
                'order' => $order
            ]);

        } catch (\Exception $e) {
            return $this->sendFailRes($e, 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // TODO: fix bug , using db transaction
            // validate
            $validateData = $request->validate([
                'products' => 'required',
                'payment_method' => 'required'
            ]);

            $products = json_decode($validateData['products']);

            // $product = Product::findOrFail($validateData['product_id']);

            if(is_null(auth()->user()->address)) throw new \Exception('Please add your address', 401);

            // update stock
            // $product->stock -= $validateData['quantity'];
            // $product->save();

            // add to orders
            $order = Order::create([
                'customer_id' => auth()->user()->id,
                'shipping_address' => auth()->user()->address,
                'shipping_cost' => Order::SHIPPING_COST,
                'order_status' => Order::PENDING,
                'payment_method' => $validateData['payment_method'],
                'total_price' => Order::SHIPPING_COST, // $product->price * $validateData['quantity'] +
            ]);

            foreach ($products as $product) {
                $prd = Product::findOrFail($product->id);
                if ($prd->stock <= 0) throw new \Exception('prd out of stock', 401);

                if ($prd->stock < $product->quantity) throw new \Exception('Insufficient stock', 401);

                // add to orderItems
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $product->quantity,
                    'unit_price' => $product->price
                ]);

                $order->total_price += $product->quantity * $product->price;
                $order->save();

                $prd->stock -= $product->quantity;
                $prd->save();
            }



            return $this->sendRes([
                'message' => 'Order created successfully',
                'order' => $order
            ]);
        } catch (\Exception $e) {
            return $this->sendFailRes($e);
        }
    }
}
