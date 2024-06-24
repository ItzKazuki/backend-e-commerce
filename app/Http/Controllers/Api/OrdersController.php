<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Services\MidtransService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller
{
    protected $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }

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
            DB::beginTransaction();

            $validateData = $request->validate([
                'products' => 'required',
                'payment_method' => 'required'
            ]);

            $products = json_decode($validateData['products']);

            if (is_null(auth()->user()->address)) throw new \Exception('Please add your address', 401);

            // add history payment
            $payment = Payment::create([
                'user_id' => auth()->user()->id,
                'invoice_number' => 'INV',
                'payment_method' => $validateData['payment_method'],
                'payment_status' => Payment::PENDING,
                'payment_amount' => 0
            ]);

            // add to orders
            $order = Order::create([
                'customer_id' => auth()->user()->id,
                'payment_id' => $payment->id,
                'shipping_address' => auth()->user()->address,
                'shipping_cost' => Order::SHIPPING_COST,
                'order_status' => Order::PENDING,
                'payment_method' => $validateData['payment_method'],
                'total_price' => Order::SHIPPING_COST, // $product->price * $validateData['quantity'] +
            ]);

            foreach ($products as $product) {
                $prd = Product::findOrFail($product->id);
                if ($prd->stock <= 0) throw new \Exception('product out of stock', 401);

                if ($prd->stock < $product->quantity) throw new \Exception('Insufficient stock', 401);

                // add to orderItems
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $product->quantity,
                    'unit_price' => $product->price
                ]);

                // set total price order
                $order->total_price += $product->quantity * $product->price;
                $order->save();

                // decrease stock
                $prd->stock -= $product->quantity;
                $prd->save();
            }

            $payment->payment_amount = $order->total_price;
            $payment->save();

            // add invoicement
            $invoice = Invoice::create([
                'payment_id' => $payment->id,
                'invoice_number' => 'INV/' . date('Y') . '/' . strtoupper(uniqid()),
                'customer_name' => auth()->user()->name,
                'invoice_amount' => $order->total_price,
            ]);

            $payment->invoice_number = $invoice->invoice_number;
            $payment->save();

            // do get snap token here
            $payment_redirect = $this->paymentMidtrans($payment, $products, $order);

            DB::commit();

            return $this->sendRes([
                'message' => 'Order created successfully',
                'url' => $payment_redirect,
                'order' => $order
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendFailRes($e);
        }
    }

    public function paymentMidtrans($payment, $products, $order)
    {
        $params = $this->midtrans->paramsGenerator($products, $order);

        if($order->payment_method == 'qris' || $payment->payment_method == 'qris') {
            return $this->midtrans->getPaymentMidtrans($params); // return redirect_url and token
        }

        if($order->payment_method == 'gopay' || $payment->payment_method == 'gopay') {
            return $this->midtrans->getPaymentGopay($params); // return token, url, etc...
        }

        if($order->payment_method == 'cod' || $payment->payment_method == 'cod') {
            return [
                'redirect_url' => route('payment.wait-confirm', ['order_id' => $order->id])
            ];
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Payment method not found',
        ], 400);
    }
}
