<?php

namespace App\Services;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Order;
use Midtrans\CoreApi;
use App\Models\Payment;
use Midtrans\Transaction;
use Illuminate\Support\Facades\Auth;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function paramsGenerator(array $products, Order $order)
    {
        $user = Auth::user();
        $item_details = [];

        $customerDetails = [
            'first_name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'shipping_address' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'address1' => $user->address,
                'country' => 'ID'
            ]
        ];

        $transcation_details = [
            'order_id' => $order->id,
            'gross_amount' => $order->total_price,
        ];

        foreach ($products as $product) {
            $item_details[] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $product->quantity,
            ];
        }

        // add shiiping fee
        $shipping_fee = [
            'id' => 'SHP-' . uniqid(),
            'name' => 'Shippment Fee',
            'price' => $order->shipping_cost,
            'quantity' => 1,
        ];

        $item_details[] = $shipping_fee;

        return [
            'customer_details' => $customerDetails,
            'transaction_details' => $transcation_details,
            'item_details' => $item_details,
        ];
    }

    public function getPaymentMidtrans($params) {
        $payment_list = Payment::MIDTRANS_PAYMENTS;

        $redirectUrl = [
            'shopeepay' => [
                'callback_url' => config('app.url') . '/payment/success?order_id=' . $params['transaction_details']['order_id'] . '&code=201',
            ],
            'finish' => config('app.url') . '/payment/success',
        ];

        return Snap::createTransaction(array_merge($params, $payment_list, $redirectUrl));
    }

    public function getPaymentGopay($params)
    {
        $gopay = [
            'payment_type' => 'gopay',
            'gopay' => array(
                'enable_callback' => true,                // optional
                'callback_url' => config('app.url') . '/payment/success'   // optional
            )
        ];

        // get gopay qr
        $transaction = CoreApi::charge(array_merge($params, $gopay));

        // return only payment type, total price, status, qr, redirect to gopay, status, and cancel payment.
        return [
            'transaction_id' => $transaction->transaction_id,
            'payment_type' => $transaction->payment_type,
            'gross_amount' => $transaction->gross_amount,
            'transaction_status' => $transaction->transaction_status,
            'qr_payment' => $transaction->actions[0]->url,
            'gopay_app' => $transaction->actions[1]->url,
            'status_url' => $transaction->actions[2]->url,
            'cancel_url' => $transaction->actions[3]->url,
        ];
    }

    /**
     * Get Payment Details
     */
    public function getPaymentStatus(string $order_id)
    {
        $status = Transaction::status($order_id);

        return [
            'currency_code' => $status->currency,
            'order_id' => $status->order_id,
            'payment_type' => $status->payment_type,
            'signature_key' => $status->signature_key,
            'transaction_id' => $status->transaction_id,
            'transaction_status' => $status->transaction_status,
            'merchant_id' => $status->merchant_id
        ];
    }

    public function cancelPayment(string $order_id)
    {
        $status = Transaction::status($order_id);

        if($status->transaction_status == 'settlement') return redirect()->to(config('app.frontend_url'));
        if($status->merchant_id != config('midtrans.merchant_id')) return abort(402);

        $transaction_id = $status->transaction_id;

        // cancel payment
        $cancel = Transaction::cancel($transaction_id);

        if($cancel == "200") return true;
        return false; // mean cancel failed
    }
}
