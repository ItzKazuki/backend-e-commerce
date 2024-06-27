@extends('layouts.seller')

@section('content')
    <div class="pagetitle">
        <h1>Update Product</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
                <li class="breadcrumb-item active">Products</li>
                <li class="breadcrumb-item active">Update</li>
            </ol>
        </nav>
    </div>

    @session('error')
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-octagon me-1"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endsession

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Update Product Form</h5>

            <!-- Vertical Form -->
            <form class="row g-3" action="{{ route('seller.product.update', ['product' => $product->id]) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="col-12">
                    <label for="product_name" class="form-label">Name Product</label>
                    <input type="text" class="form-control" name="product_name" id="product_name"
                        value="{{ $product->product_name }}">
                </div>
                <div class="col-12">
                    <label for="product_desc" class="col-sm-2 col-form-label">Description Product</label>
                    <div class="col-sm-10">
                        <textarea id="product_desc" name="product_desc" class="form-control" style="height: 100px">{{ $product->product_desc }}</textarea>
                    </div>
                </div>
                <div class="col-12">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" class="form-control" name="price" id="price" value="{{ $product->price }}">
                </div>
                <div class="col-12">
                    <label for="stock" class="form-label">Stock</label>
                    <input type="number" class="form-control" name="stock" id="stock" value="{{ $product->stock }}">
                </div>
                <div class="col-12">
                    <label for="brand" class="form-label">Brand</label>
                    <input type="text" class="form-control" name="brand" id="brand" value="{{ $product->brand }}">
                </div>
                <div class="col-12">
                    <label for="product_image" class="form-label">Product Image</label>
                    <input type="file" class="form-control" name="product_image" id="product_image">
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
            </form><!-- Vertical Form -->

        </div>
    </div>
@endsection
