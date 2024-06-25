<?php

namespace App\Http\Controllers\Seller;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sales = Order::whereHas('orderItems.product.seller', function ($query) {
            $query->where('id', auth()->user()->id);
        })->get();

        $salesSum = $sales->sum('total_price');

        $userCount = $sales->pluck('customer_id')->unique()->count();

        $revenue = number_format(intval($salesSum), '0', ',', '.');

        $countSales = $sales->count();

        $today = Carbon::today();

        $countSalesToday = Order::whereHas('orderItems.product.seller', function ($query) {
            $query->where('id', auth()->user()->id);
        })
            ->whereDate('created_at', $today)
            ->count();

        $topSellingProducts = Product::where('seller_id', auth()->user()->id)
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select('products.*', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.seller_id', 'products.product_name', 'products.product_desc', 'products.price', 'products.stock', 'products.brand', 'products.upload_id', 'products.created_at', 'products.updated_at')
            ->orderBy('total_sold', 'desc')
            ->take(5) // Get the top 5 selling products
            ->get();


        $previousDaySales = Order::whereHas('orderItems.product.seller', function ($query) {
            $query->where('id', auth()->user()->id);
        })
            ->whereDate('created_at', Carbon::yesterday())
            ->count();

        $previousMonthRevenue = Order::whereHas('orderItems.product.seller', function ($query) {
            $query->where('id', auth()->user()->id);
        })
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->sum('total_price');

        $previousYearCustomers = Order::whereHas('orderItems.product.seller', function ($query) {
            $query->where('id', auth()->user()->id);
        })
            ->whereYear('created_at', Carbon::now()->subYear()->year)
            ->pluck('customer_id')
            ->unique()
            ->count();

        // Calculate percentage changes
        $salesIncrease = $previousDaySales > 0 ? (($countSalesToday - $previousDaySales) / $previousDaySales * 100) : 0;
        $revenueIncrease = $previousMonthRevenue > 0 ? (($revenue - $previousMonthRevenue) / $previousMonthRevenue * 100) : 0;
        $customerIncrease = $previousYearCustomers > 0 ? (($userCount - $previousYearCustomers) / $previousYearCustomers * 100) : 0;

        return view('sellers.dashboard', [
            'sales' => $sales,
            'countSales' => $countSalesToday,
            'revenue' => $revenue,
            'userCount' => $userCount,
            'topSellingProducts' => $topSellingProducts,
            'previousDaySales' => $previousDaySales,
            'previousMonthRevenue' => $previousMonthRevenue,
            'previousYearCustomers' => $previousYearCustomers,
            'salesIncrease' => $salesIncrease,
            'revenueIncrease' => $revenueIncrease,
            'customerIncrease' => $customerIncrease,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
