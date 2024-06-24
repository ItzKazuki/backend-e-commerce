<?php

namespace App\Http\Controllers\Payments;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\MidtransService;

class PaymentController extends Controller
{
    protected $midtrans;

    public function __construct(MidtransService $midtrans) {
        $this->midtrans = $midtrans;
    }

    public function process(Request $request, $order_id) {
        // find order id
        // check payment_type only gopay, qris, and spay
        return view('payments.process');
    }

    public function success(Request $request) {
        $paymentStatus = $this->midtrans->getPaymentStatus($request->order_id);
        $order = Order::findOrFail($request->order_id);

        if($paymentStatus['merchant_id'] !== config('midtrans.merchant_id')) abort(402);
        if($order->payment->payment_status == Payment::COMPLETED) return redirect()->to(config('app.frontend_url'));

        // update order status
        $order->order_status = Order::PROCESSING;
        $order->payment_method = $paymentStatus['payment_type'];
        $order->save();

        //update payment
        $order->payment()->update([
            'payment_method' => $paymentStatus['payment_type'],
            'payment_status' => Payment::COMPLETED
        ]);

        return view('payments.success', [
            'order' => $order
        ]);
    }

    public function cancel(Request $request) {
        $order = Order::findOrFail($request->order_id);

        $status = $this->midtrans->cancelPayment($order->id);

        if($status) {
            $order->order_status = Order::CANCELLED;
            $order->save();

            $order->payment()->update([
                'payment_status' => Payment::CANCELLED
            ]);

            return view('payments.cancel', [
                'order' => $order
            ]);
        }

        return abort(400);
    }

    public function waitConfirm(Request $request) {
        $order = Order::findOrFail($request->order_id);

        if($order->payment_method != 'cod') return abort(402);

        $order->order_status = Order::PROCESSING;
        $order->save();

        $order->payment()->update([
            'payment_status' => Payment::COMPLETED
        ]);

        return view('payments.wait-confirm', [
            'order' => $order
        ]);
    }

    public function pending(Request $request) {
        $order = Order::findOrFail($request->order_id);

        if($order->order_status != Order::PENDING) return redirect()->to(config('app.frontend_url'));

        return view('payments.pending', [
            'order' => $order
        ]);
    }
}
