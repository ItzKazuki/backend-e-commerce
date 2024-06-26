@extends('layouts.payment')

@section('header',)
<meta http-equiv="refresh" content="5; url={{ config('app.frontend_url') }}">
@endsection

@section('content')
<div class="paper">
    <div class="main-contents">
        <div class="icon danger">&#10006;</div>
        <div class="title">
            Payment Error
        </div>
        <div class="description">
            Your payment failed, please try again later or contact us for more information.
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
