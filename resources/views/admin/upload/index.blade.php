@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>User Uploads</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.uploads.index') }}">User Uploads</a></li>
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
            <h5 class="card-title">List User Uploads File</h5>

            <!-- Table with stripped rows -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Id</th>
                        <th scope="col">Image</th>
                        <th scope="col">Uploader</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($uploads as $key => $upload)
                        <tr>
                            <th scope="row">{{ $key + 1 }}</th>
                            <td>{{ $upload->id }}</td>
                            <td><img src="{{ $upload->image }}" width="100" alt=""></td>
                            <td>{{ $upload->user->name }}</td>
                            <td><a href="{{ route('admin.uploads.show', ['id' => $upload->id]) }}"
                                    class="btn btn-primary btn-sm" title="Show User Detail"><i class="bi bi-eye"></i></a>
                                <a href="#" class="btn btn-danger btn-sm"
                                    onclick="event.preventDefault(); if(confirm('Apakah Anda Yakin ?')){document.getElementById('remove-{{ $upload->id }}-form').submit();}"
                                    title="Remove User"><i class="bi bi-trash"></i></a>

                                <form id="remove-{{ $upload->id }}-form"
                                    action="{{ route('admin.uploads.destroy', ['id' => $upload->id]) }}" method="POST"
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
