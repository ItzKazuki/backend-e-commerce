<?php

namespace App\Http\Controllers\Payments;

use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Services\MidtransService;
use App\Http\Controllers\Controller;
use App\Notifications\Order\OrderChanged;
use App\Notifications\Payment\PaymentCancel;
use App\Notifications\Payment\PaymentSuccess;

class PaymentController extends Controller
{
    protected $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }

    public function process(Request $request, $order_id)
    {
        // find order id
        // check payment_type only gopay, qris, and spay
        return view('payments.process');
    }

    public function success(Request $request)
    {
        $paymentStatus = $this->midtrans->getPaymentStatus($request->order_id);
        $order = Order::findOrFail($request->order_id);

        if ($paymentStatus['merchant_id'] !== config('midtrans.merchant_id')) abort(402);
        if ($order->payment->payment_status == Payment::COMPLETED) return redirect()->to(config('app.frontend_url'));
        if ($paymentStatus['transaction_status'] != 'settlement') return redirect()->route('payments.error', ['order_id', $request->order_id]);

        // update order status
        $order->update([
            'order_status' => Order::PROCESSING,
            'payment_method' => $paymentStatus['payment_type'],
        ]);

        //update payment
        $order->payment()->update([
            'payment_method' => $paymentStatus['payment_type'],
            'payment_status' => Payment::COMPLETED
        ]);

        $user = User::findOrFail($order->user->id);

        $order->user->notify(new PaymentSuccess(Payment::find($order->payment->id)));
        $order->user->notify(new OrderChanged($user, $order, Order::PROCESSING));

        return view('payments.success', [
            'title' => 'Success',
            'order' => $order
        ]);
    }

    public function cancel(Request $request)
    {
        $order = Order::findOrFail($request->order_id);

        $status = $this->midtrans->cancelPayment($order->id);

        if ($status) {
            $order->update([
                'order_status' => Order::CANCELLED
            ]);

            $order->payment()->update([
                'payment_status' => Payment::CANCELLED
            ]);

            $user = User::findOrFail($order->user->id);

            $order->user->notify(new PaymentCancel($order));
            $order->user->notify(new OrderChanged($user, $order, Order::CANCELLED));

            return view('payments.cancel', [
                'title' => 'Cancel',
                'order' => $order
            ]);
        }

        return abort(400);
    }

    public function waitConfirm(Request $request)
    {
        $order = Order::findOrFail($request->order_id);

        if ($order->payment_method != 'cod') return abort(402);

        $order->order_status = Order::PROCESSING;
        $order->save();

        $order->payment()->update([
            'payment_status' => Payment::COMPLETED
        ]);

        return view('payments.wait-confirm', [
            'title' => 'Wait Confirm',
            'order' => $order
        ]);
    }

    public function pending(Request $request)
    {
        $order = Order::findOrFail($request->order_id);

        if ($order->order_status != Order::PENDING) return redirect()->to(config('app.frontend_url'));

        $paymentStatus = $this->midtrans->getPaymentStatus($request->order_id);

        if ($paymentStatus['transaction_status'] == 'settlement') return redirect()->route('payments.success', ['order_id', $request->order_id]);

        return view('payments.pending', [
            'title' => 'Pending',
            'order' => $order
        ]);
    }

    public function error()
    {
        return view('payments.error', [
            'title' => 'Error'
        ]);
    }
}
