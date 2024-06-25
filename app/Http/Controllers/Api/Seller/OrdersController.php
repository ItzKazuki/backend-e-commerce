<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

/**
 * @group Seller
 *
 * API for create, view, all product and order.
 * @authenticated
 */

class OrdersController extends Controller
{
    /**
     * Show all orders by seller id
     *
     * Seller can view all order with he products
     */
    public function index()
    {
        return $this->sendRes([
            'orders' => Order::whereHas('orderItems.product.seller', function($query) {
                $query->where('id', auth()->user()->id);
            })->get()
        ]);
    }
    /**
     * Update orders
     *
     * Seller can update status order with spesific id
     * @urlParam id required The id order. Example: ORD-cccc
     *
     */
    public function update(Request $request, string $id)
    {
        // TODO: Implement update() method.
    }
}
