@php
    $layout = auth()->user()->role == 'seller' ? 'layouts.seller' : 'layouts.app';
    $dashboard = auth()->user()->role == 'seller' ? 'seller.dashboard' : 'admin.dashboard'
@endphp

@extends($layout)

@section('content')
<div class="pagetitle">
    <h1>Notifications</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route($dashboard)}}">Home</a></li>
        <li class="breadcrumb-item">Account</li>
        <li class="breadcrumb-item active">Notifications</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-6">
        @foreach ($notifications as $notif)
        <div class="card">
            <div class="card-body">
              <h5 class="card-title">{{$notif->data['title']}}</h5>
              <p>{{$notif->data['message']}}</p>
            </div>
          </div>
        @endforeach

      </div>
    </div>
  </section>
@endsection
