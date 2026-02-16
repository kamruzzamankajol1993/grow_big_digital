<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Brand;
class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // 1. General Counts
        $totalProducts = Product::count();
        $pendingRequests = Order::where('status', 'pending')->count();
        $acceptedRequests = Order::where('status', 'accepted')->count();
        $cancelledRequests = Order::where('status', 'cancelled')->count();
        $totalCustomers = Customer::count();
$totalBrands = Brand::count();
        // 2. Request History Graph Data (Last 30 Days)
        $last30DaysData = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
        
        $dates = $last30DaysData->pluck('date');
        $counts = $last30DaysData->pluck('count');

        // 3. Top 10 Products Data
        $topProducts = OrderDetail::select('product_id', DB::raw('count(*) as total_requests'))
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_requests')
            ->take(10)
            ->get();

        // Prepare arrays for Product Chart
        $topProductNames = $topProducts->map(function($item) {
            return $item->product->name ?? 'Unknown Product';
        });
        $topProductRequests = $topProducts->pluck('total_requests');

        // 4. Top 10 Brands Data
        $topBrands = DB::table('order_details')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->select('brands.name', DB::raw('count(order_details.id) as total_requests'))
            ->groupBy('brands.id', 'brands.name')
            ->orderByDesc('total_requests')
            ->take(10)
            ->get();
        
        $brandNames = $topBrands->pluck('name');
        $brandRequests = $topBrands->pluck('total_requests');

        // 5. Latest 10 Orders
        $latestRequests = Order::with('customer')->latest()->take(10)->get();

        // 6. Top 10 Customers Data
        $topCustomers = Order::select('customer_id', DB::raw('count(*) as total_requests'))
            ->with('customer')
            ->whereNotNull('customer_id')
            ->groupBy('customer_id')
            ->orderByDesc('total_requests')
            ->take(10)
            ->get();

        // Prepare arrays for Customer Chart
        $topCustomerNames = $topCustomers->map(function($item) {
            return $item->customer->name ?? 'Unknown Customer';
        });
        $topCustomerRequests = $topCustomers->pluck('total_requests');

        return view('admin.dashboard.index', compact(
            'totalBrands',
            'totalProducts',
            'pendingRequests',
            'acceptedRequests',
            'cancelledRequests',
            'totalCustomers',
            'dates',
            'counts',
            'topProductNames', // Sent for chart
            'topProductRequests', // Sent for chart
            'brandNames',
            'brandRequests',
            'latestRequests',
            'topCustomerNames', // Sent for chart
            'topCustomerRequests' // Sent for chart
        ));
    }
}