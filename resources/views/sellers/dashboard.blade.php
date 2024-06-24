@extends('layouts.seller')

@section('content')
<div class="pagetitle">
    <h1>Dashboard</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>
</div>
<div>
    <h1>Welcome to Seller Dashboard {{auth()->user()->name}}!</h1>
</div>
@endsection
