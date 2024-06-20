@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Sellers</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('sellers.index') }}">Seller</a></li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-body">
      <h5 class="card-title">List Sellers</h5>

      <!-- Table with stripped rows -->
      <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Role</th>
            <th scope="col">Phone</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($sellers as $key => $seller)
            <tr>
                <th scope="row">{{ $key+1 }}</th>
                <td>{{ $seller->name }}</td>
                <td>{{ ucfirst($seller->role) }}</td>
                <td>{{ $seller->phone }}</td>
                <td><a href="{{ route('sellers.show', ['seller' => $seller->id]) }}" class="btn btn-primary btn-sm" title="Show User Detail"><i class="bi bi-eye"></i></a>
                    <a href="{{ route('sellers.edit', ['seller' => $seller->id]) }}" class="btn btn-warning btn-sm" title="Edit User"><i class="bi bi-pen"></i></a>
                    <a href="#" class="btn btn-danger btn-sm" title="Remove User"><i class="bi bi-trash"></i></a></td>
            </tr>
          @endforeach
        </tbody>
      </table>
      <!-- End Table with stripped rows -->

    </div>
  </div>
@endsection
