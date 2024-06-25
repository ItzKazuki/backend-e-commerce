<?php

namespace App\Http\Controllers\Api\Payments;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

/**
 * @group Payments
 *
 * API for create, view, all payments.
 * @authenticated
 */

class PaymentController extends Controller
{
    /**
     * Show all Payment method
     *
     *  user can show all available payment method
     */
    public function showPaymentList()
    {
        return $this->sendRes([
            'payments' => Payment::PAYMENT_METHODS
        ]);
    }

    /**
     * Show Payment using spesific id
     *
     * user can show payment with spesific id
     * @urlParam id required The id payment. Example: PYMT-aibabasc
     *
     */
    public function show(Request $request, string $id)
    {
        try {
            $payment = Payment::where('user_id', $request->user()->id)->findOrFail($id);

            return $this->sendRes([
                'payment' => $payment
            ]);
        } catch (\Exception $e) {
            return $this->sendFailRes($e);
        }
    }
}
