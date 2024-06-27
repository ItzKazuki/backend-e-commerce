use App\Models\Order;
@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Orders</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
            </ol>
        </nav>
    </div>

    @session('success')
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-1"></i>
        Success create new seller.product.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endsession

    @session('error')
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-octagon me-1"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endsession

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">List Orders</h5>

            <!-- Table with stripped rows -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">ID</th>
                        <th scope="col">Status</th>
                        <th scope="col">Customer Name</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $key => $order)
                        <tr>
                            <th scope="row">{{ $key + 1 }}</th>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->order_status }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td><a href="{{ route('admin.orders.show', ['order' => $order->id]) }}"
                                    class="btn btn-primary btn-sm" title="Show User Detail"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('admin.orders.edit', ['order' => $order->id]) }}"
                                    class="btn btn-warning btn-sm" title="Edit User"><i class="bi bi-pen"></i></a>
                                <a href="#" class="btn btn-danger btn-sm" title="Remove User"><i
                                        class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- End Table with stripped rows -->

        </div>
    </div>
@endsection
