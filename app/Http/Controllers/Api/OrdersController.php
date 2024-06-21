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
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1'
            ]);

            $product = Product::findOrFail($validateData['product_id']);

            if(is_null(auth()->user()->address)) throw new \Exception('Please add your address', 401);

            if ($product->stock <= 0) throw new \Exception('Product out of stock', 401);

            if ($product->stock < $validateData['quantity']) throw new \Exception('Insufficient stock', 401);

            // update stock
            $product->stock -= $validateData['quantity'];
            $product->save();

            // add to orders
            $order = Order::create([
                'customer_id' => auth()->user()->id,
                'shipping_address' => auth()->user()->address,
                'shipping_cost' => Order::SHIPPING_COST,
                'order_status' => Order::PENDING,
                'payment_method' => 'QRIS',
                'quantity' => $validateData['quantity'],
                'total_price' => $product->price * $validateData['quantity'] + Order::SHIPPING_COST,
            ]);

            // add to orderItems
            $orderItems = OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $validateData['product_id'],
                'quantity' => $validateData['quantity'],
                'unit_price' => $product->price
            ]);

            return $this->sendRes([
                'message' => 'Order created successfully',
                'order' => $order
            ]);
        } catch (\Exception $e) {
            return $this->sendFailRes($e);
        }
    }
}
