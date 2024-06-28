@php
    $layout = auth()->user()->role == 'seller' ? 'layouts.seller' : 'layouts.app';
@endphp

@extends($layout)

@section('content') 
@dd($notification)
@endsection