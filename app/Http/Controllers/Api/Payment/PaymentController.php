<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function paymentSuccess(Request $request) {
        $order = Order::findOrFail($request->order_id);
        $order->order_status = Order::PROCESSING;
        $order->save();

        return view('payments.success', [
            'order' => $order
        ]);
    }
}
