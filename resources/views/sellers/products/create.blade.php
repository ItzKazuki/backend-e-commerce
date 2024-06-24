@extends('layouts.seller')

@section('content')
    <div class="pagetitle">
        <h1>Create Product</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
                <li class="breadcrumb-item active">Products</li>
                <li class="breadcrumb-item active">Create</li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Create New Product Form</h5>

            <!-- Vertical Form -->
            <form class="row g-3" action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="col-12">
                    <label for="product_name" class="form-label">Name Product</label>
                    <input type="text" class="form-control" name="product_name" id="product_name">
                </div>
                <div class="col-12">
                    <label for="product_desc" class="form-label">Description Product</label>
                    <input type="product_desc" class="form-control" name="product_desc" id="product_desc">
                </div>
                <div class="col-12">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" class="form-control" name="price" id="price">
                </div>
                <div class="col-12">
                    <label for="stock" class="form-label">Stock</label>
                    <input type="number" class="form-control" name="stock" id="stock">
                </div>
                <div class="col-12">
                    <label for="brand" class="form-label">Brand</label>
                    <input type="text" class="form-control" name="brand" id="brand">
                </div>
                <div class="col-12">
                    <label for="product_image" class="form-label">Brand</label>
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
