@extends('layouts.seller')

@section('content')
    <div class="pagetitle">
        <h1>Products</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
                <li class="breadcrumb-item active">Products</li>
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
            <h5 class="card-title">List Products</h5>

            <!-- Table with stripped rows -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Price</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $key => $product)
                        <tr>
                            <th scope="row">{{ $key + 1 }}</th>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->product_name }}</td>
                            <td>{{ $product->price }}</td>
                            <td><a href="{{ route('seller.product.show', ['product' => $product->id]) }}"
                                    class="btn btn-primary btn-sm" title="Show Product Detail"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('seller.product.edit', ['product' => $product->id]) }}"
                                    class="btn btn-warning btn-sm" title="Edit Product"><i class="bi bi-pen"></i></a>
                                <a href="#" class="btn btn-danger btn-sm"
                                    onclick="event.preventDefault(); if(confirm('Apakah Anda Yakin ?')){document.getElementById('remove-{{ $product->id }}-form').submit();}"
                                    title="Remove Product"><i class="bi bi-trash"></i></a>

                                <form id="remove-{{ $product->id }}-form"
                                    action="{{ route('seller.product.destroy', ['product' => $product->id]) }}"
                                    method="POST" style="display: none;">
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
