@extends('layouts.payment')

@section('header',)
<meta http-equiv="refresh" content="5; url={{ config('app.frontend_url') }}">
@endsection

@section('content')
<div class="paper">
    <div class="main-contents">
        <div class="icon success">&#10004;</div>
        <div class="title">
            Payment Complete
        </div>
        <div class="description">
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
