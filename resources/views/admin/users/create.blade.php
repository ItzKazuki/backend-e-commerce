@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Users</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
                <li class="breadcrumb-item">Create</li>
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
            <h5 class="card-title">Create New User Form</h5>

            <!-- Vertical Form -->
            <form class="row g-3" action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <div class="col-12">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" name="name" id="name">
                </div>
                <div class="col-12">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="email">
                </div>
                <div class="col-12">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" name="phone" id="phone">
                </div>
                <div class="col-12">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="password">
                </div>
                <div class="col-12">
                    <label for="inputAddress" class="form-label">Role</label>
                    <div class="col-sm-10">
                        <select class="form-select" id="role" name="role" aria-label="Select User Role">
                            <option selected>Open this select menu</option>
                            <option value="{{ \App\Models\User::CUSTOMER }}">Customer</option>
                            <option value="{{ \App\Models\User::SELLER }}">Seller</option>
                            <option value="{{ \App\Models\User::ADMIN }}">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
            </form><!-- Vertical Form -->

        </div>
    </div>
@endsection
