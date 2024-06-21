<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index()
    {
        return $this->sendRes([
            'orders' => Order::whereHas('orderItems.product.seller', function($query) {
                $query->where('id', auth()->user()->id);
            })->get()
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // TODO: Implement update() method.
    }
}
