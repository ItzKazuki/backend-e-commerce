@extends('layouts.payment')

@section('content')
<div class="paper">
    <div class="main-contents">
        <div class="success-icon">&#10004;</div>
        <div class="success-title">
            Payment Complete
        </div>
        <div class="success-description">
            Thank you for completing the payment! You will shortly receive an email of your payment.
        </div>
        <div class="order-details">
            <div class="order-number-label">Transaction ID</div>
            <div class="order-number">{{ $order->id }}</div>
            <div class="complement">Thank You!</div>
        </div>
    </div>
    <div class="jagged-edge"></div>
</div>
@endsection
