<?php

namespace App\Http\Controllers\Payments;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\MidtransService;

class PaymentController extends Controller
{
    protected $midtrans;

    public function __construct(MidtransService $midtrans) {
        $this->midtrans = $midtrans;
    }

    public function paymentSuccess(Request $request) {
        dd($this->midtrans->getPaymentStatus($request->order_id));

        $paymentStatus = $this->midtrans->getPaymentStatus($request->order_id);
        $order = Order::findOrFail($request->order_id);

        if($order->payment()->payment_status    )

        $order->order_status = Order::PROCESSING;
        $order->save();

        return view('payments.success', [
            'order' => $order
        ]);
    }
}
