@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Users</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
            <li class="breadcrumb-item">Edit</li>
            <li class="breadcrumb-item"><a href="{{ route('users.edit', ['user' => $user->id]) }}">{{ $user->id }}</a></li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-body">
      <h5 class="card-title">Edit User Form</h5>

      <!-- Vertical Form -->
      <form class="row g-3" action="{{ route('users.update', ['user' => $user->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="col-12">
          <label for="name" class="form-label">Name</label>
          <input type="text" class="form-control" name="name" id="name" value="{{ $user->name }}">
        </div>

        <div class="col-12">
          <label for="email" class="form-label">Email</label>
          <input type="email" class="form-control" name="email" id="email" value="{{ $user->email }}">
        </div>

        <div class="col-12">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" name="phone" id="phone" value="{{ $user->phone }}">
        </div>

        <div class="col-12">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" name="password" id="password">
        </div>

        <div class="col-12">
          <label for="inputAddress" class="form-label">Role</label>
          <div class="col-sm-10">
            <select class="form-select" id="role" name="role" value="{{ $user->role }}" aria-label="Select Role User">
              <option >Open this select menu</option>
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
