@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Users</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
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
            <h5 class="card-title">List Users</h5>

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
                    @foreach ($users as $key => $user)
                        <tr>
                            <th scope="row">{{ $key + 1 }}</th>
                            <td>{{ $user->name }}</td>
                            <td>{{ ucfirst($user->role) }}</td>
                            <td>{{ $user->phone }}</td>
                            <td><a href="{{ route('admin.users.show', ['user' => $user->id]) }}"
                                    class="btn btn-primary btn-sm" title="Show User Detail"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('admin.users.edit', ['user' => $user->id]) }}"
                                    class="btn btn-warning btn-sm" title="Edit User"><i class="bi bi-pen"></i></a>
                                <a href="#" class="btn btn-danger btn-sm"
                                    onclick="event.preventDefault(); if(confirm('Apakah Anda Yakin ?')){document.getElementById('remove-{{ $user->id }}-form').submit();}"
                                    title="Remove User"><i class="bi bi-trash"></i></a>

                                <form id="remove-{{ $user->id }}-form"
                                    action="{{ route('admin.users.destroy', ['user' => $user->id]) }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- End Table with stripped rows -->

        </div>
    </div>
@endsection
