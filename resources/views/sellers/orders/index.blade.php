@extends('layouts.seller')

@section('content')
<div class="pagetitle">
    <h1>Orders</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
            <li class="breadcrumb-item active">Orders</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-body">
      <h5 class="card-title">List Orders</h5>

      <!-- Table with stripped rows -->
      <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">ID</th>
            <th scope="col">Payment Method</th>
            <th scope="col">Status</th>
            <th scope="col">Subtotal</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($orders as $key => $order)
            <tr>
                <th scope="row">{{ $key+1 }}</th>
                <td>{{ $order->id }}</td>
                <td>{{ $order->payment_method }}</td>
                <td>{{ $order->order_status }}</td>
                <td>{{ $order->total_price }}</td>
                <td><a href="{{ route('seller.orders.show', ['order' => $order->id]) }}" class="btn btn-primary btn-sm" title="Show User Detail"><i class="bi bi-eye"></i></a>
                    <a href="{{ route('seller.orders.edit', ['order' => $order->id]) }}" class="btn btn-warning btn-sm" title="Edit User"><i class="bi bi-pen"></i></a>
                    <a href="#" class="btn btn-danger btn-sm" onclick="event.preventDefault(); if(confirm('Apakah Anda Yakin ?')){document.getElementById('remove-{{$order->id}}-form').submit();}" title="Remove order"><i class="bi bi-trash"></i></a>

                    <form id="remove-{{$order->id}}-form" action="{{ route('seller.orders.destroy', ['order' => $order->id]) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
            </tr>
          @endforeach
        </tbody>
      </table>
      <!-- End Table with stripped rows -->

    </div>
  </div>
@endsection
