@extends('layouts.seller')

@section('content')
<div class="pagetitle">
    <h1>Dashboard</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>
</div>
<div>
    <h1>Welcome to Seller Dashboard {{auth()->user()->name}}!</h1>
</div>
<div class="section dashboard">
    <div class="row">
        <div class="col-lg">
            <div class="row">

              <!-- Sales Card -->
              <div class="col-xxl-4 col-md-6">
                <div class="card info-card sales-card">

                  <div class="filter">
                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                      <li class="dropdown-header text-start">
                        <h6>Filter</h6>
                      </li>

                      <li><a class="dropdown-item" href="#">Today</a></li>
                      <li><a class="dropdown-item" href="#">This Month</a></li>
                      <li><a class="dropdown-item" href="#">This Year</a></li>
                    </ul>
                  </div>

                  <div class="card-body">
                    <h5 class="card-title">Sales <span>| Today</span></h5>

                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-cart"></i>
                      </div>
                      <div class="ps-3">
                        <h6>{{ $countSales }}</h6>
                        <span class="text-{{ $salesIncrease >= 0 ? 'success' : 'danger' }} small pt-1 fw-bold">{{ number_format($salesIncrease, 2) }}%</span>
                        <span class="text-muted small pt-2 ps-1">{{ $salesIncrease >= 0 ? 'increase' : 'decrease' }}</span>
                    </div>
                    </div>
                  </div>

                </div>
              </div><!-- End Sales Card -->

              <!-- Revenue Card -->
              <div class="col-xxl-4 col-md-6">
                <div class="card info-card revenue-card">

                  <div class="filter">
                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                      <li class="dropdown-header text-start">
                        <h6>Filter</h6>
                      </li>

                      <li><a class="dropdown-item" href="#">Today</a></li>
                      <li><a class="dropdown-item" href="#">This Month</a></li>
                      <li><a class="dropdown-item" href="#">This Year</a></li>
                    </ul>
                  </div>

                  <div class="card-body">
                    <h5 class="card-title">Revenue <span>| This Month</span></h5>

                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-currency-dollar"></i>
                      </div>
                      <div class="ps-3">
                        <h6>Rp. {{ $revenue }}</h6>
                        <span class="text-{{ $revenueIncrease >= 0 ? 'success' : 'danger' }} small pt-1 fw-bold">{{ number_format($revenueIncrease, 2) }}%</span>
                        <span class="text-muted small pt-2 ps-1">{{ $revenueIncrease >= 0 ? 'increase' : 'decrease' }}</span>
                    </div>
                    </div>
                  </div>

                </div>
              </div><!-- End Revenue Card -->

              <!-- Customers Card -->
              <div class="col-xxl-4 col-xl-12">

                <div class="card info-card customers-card">

                  <div class="filter">
                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                      <li class="dropdown-header text-start">
                        <h6>Filter</h6>
                      </li>

                      <li><a class="dropdown-item" href="#">Today</a></li>
                      <li><a class="dropdown-item" href="#">This Month</a></li>
                      <li><a class="dropdown-item" href="#">This Year</a></li>
                    </ul>
                  </div>

                  <div class="card-body">
                    <h5 class="card-title">Customers <span>| This Year</span></h5>

                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-people"></i>
                      </div>
                      <div class="ps-3">
                        <h6>{{ $userCount }}</h6>
                        <span class="text-{{ $customerIncrease >= 0 ? 'success' : 'danger' }} small pt-1 fw-bold">{{ number_format($customerIncrease, 2) }}%</span>
                        <span class="text-muted small pt-2 ps-1">{{ $customerIncrease >= 0 ? 'increase' : 'decrease' }}</span>
                    </div>
                    </div>

                  </div>
                </div>

              </div><!-- End Customers Card -->

              <!-- Recent Sales -->
              <div class="col-12">
                <div class="card recent-sales overflow-auto">

                  <div class="filter">
                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                      <li class="dropdown-header text-start">
                        <h6>Filter</h6>
                      </li>

                      <li><a class="dropdown-item" href="#">Today</a></li>
                      <li><a class="dropdown-item" href="#">This Month</a></li>
                      <li><a class="dropdown-item" href="#">This Year</a></li>
                    </ul>
                  </div>

                  <div class="card-body">
                    <h5 class="card-title">Recent Sales <span>| Today</span></h5>

                    <table class="table table-borderless datatable">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Customer</th>
                          <th scope="col">Product</th>
                          <th scope="col">Price</th>
                          <th scope="col">Payment Status</th>
                          <th scope="col">Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($sales as $key => $sale)
                        <tr>
                            <th scope="row"><a href="#">{{$key + 1}}</a></th>
                            <td>{{ $sale->user->name }}</td>
                            <td><a href="#" class="text-primary">{{$sale->orderItems[0]->product->product_name}}</a></td>
                            <td>Rp. {{ number_format(intval($sale->total_price), 0, ',', '.') }}</td>
                            <td><span class="badge bg-{{ $sale->payment->payment_status == \App\Models\Payment::COMPLETED ? 'success' : ($sale->payment->payment_status == \App\Models\Payment::PENDING ? 'warning text-dark' : 'danger') }}">{{$sale->payment->payment_status}}</span></td>
                            <td><span class="badge bg-{{ $sale->order_status == \App\Models\Order::DELIVERED ? 'success' : ($sale->order_status == \App\Models\Order::PENDING || $sale->order_status == \App\Models\Order::PROCESSING  ? 'warning text-dark' : 'danger') }}">{{$sale->order_status}}</span></td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>

                  </div>

                </div>
              </div><!-- End Recent Sales -->

              <!-- Top Selling -->
              <div class="col-12">
                <div class="card top-selling overflow-auto">

                  <div class="filter">
                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                      <li class="dropdown-header text-start">
                        <h6>Filter</h6>
                      </li>

                      <li><a class="dropdown-item" href="#">Today</a></li>
                      <li><a class="dropdown-item" href="#">This Month</a></li>
                      <li><a class="dropdown-item" href="#">This Year</a></li>
                    </ul>
                  </div>

                  <div class="card-body pb-0">
                    <h5 class="card-title">Top Selling <span>| Today</span></h5>

                    <table class="table table-borderless">
                      <thead>
                        <tr>
                          <th scope="col">Preview</th>
                          <th scope="col">Product</th>
                          <th scope="col">Price</th>
                          <th scope="col">Sold</th>
                          <th scope="col">Revenue</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($topSellingProducts as $key => $topSellingProduct )
                        <tr>
                          <th scope="row"><a href="#"><img src="{{ $topSellingProduct->upload->image }}" alt=""></a></th>
                          <td><a href="#" class="text-primary fw-bold">{{ $topSellingProduct->product_name }}</a></td>
                          <td>Rp. {{ number_format(intval($topSellingProduct->price), 0, ',', '.') }}</td>
                          <td class="fw-bold">{{ $topSellingProduct->total_sold }}</td>
                          <td>Rp. {{ number_format(intval($topSellingProduct->price * $topSellingProduct->total_sold), 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>

                  </div>

                </div>
              </div><!-- End Top Selling -->

            </div>
          </div><!-- End Left side columns -->
    </div>
</div>
@endsection
