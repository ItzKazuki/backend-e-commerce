@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>User Uploads</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">User Uploads</a></li>
        </ol>
    </nav>
</div>

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
                <th scope="row">{{ $key+1 }}</th>
                <td>{{ $upload->id }}</td>
                <td><img src="{{ $upload->image }}" width="100" alt=""></td>
                <td>{{ $upload->user->name }}</td>
                <td><a href="{{ route('uploads.show', ['id' => $upload->id]) }}" class="btn btn-primary btn-sm" title="Show User Detail"><i class="bi bi-eye"></i></a>
                    <a href="#" class="btn btn-danger btn-sm" onclick="event.preventDefault(); if(confirm('Apakah Anda Yakin ?')){document.getElementById('remove-{{$upload->id}}-form').submit();}" title="Remove User"><i class="bi bi-trash"></i></a>

                    <form id="remove-{{$upload->id}}-form" action="{{ route('uploads.destroy', ['id' => $upload->id]) }}" method="POST" style="display: none;">
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
