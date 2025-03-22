<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;

class SalesController extends Controller
{
    public function index()
    {
        $monthlySales = Order::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(Price) as total_sales')
            ->groupBy('month')
            ->get();

        $totalSales = Order::sum('Price');
        $totalCustomers = Customer::count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();

        return view('dashboard', compact(
            'monthlySales', 'totalSales', 'totalCustomers', 'totalProducts', 'totalOrders', 'totalEarnings'
        ));
    }

    public function getWeeklySalesData()
    {
        $startDate = Carbon::now()->startOfWeek();
        $endDate = Carbon::now()->endOfWeek();

        $salesData = DB::table('orders')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(Price) as total_sales'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'ASC')
            ->get();

        return response()->json($salesData);
    }
    public function getSalesData()
    {
        $salesData = DB::table('orders')
            ->select(DB::raw('WEEK(created_at) as week, SUM(Price) as total_sales'))
            ->groupBy('week')
            ->orderBy('week')
            ->get();

        return response()->json($salesData);
    }

    public function getWeeklySales()
    {
        $salesData = DB::table('orders')
            ->selectRaw('YEAR(created_at) as year, WEEK(created_at) as week, SUM(Price) as total_sales')
            ->whereNotNull('created_at')
            ->groupBy('year', 'week')
            ->orderBy('year', 'asc')
            ->orderBy('week', 'asc')
            ->get();

        return response()->json($salesData);
    }

    public function getTotalEarnings()
    {
        $currentMonth = Carbon::now()->format('Y-m');
        $lastMonth = Carbon::now()->subMonth()->format('Y-m');

        // Calculate earnings for the current month
        $currentMonthEarnings = DB::table('orders')
            ->whereYear('created_at', '=', Carbon::now()->year)
            ->whereMonth('created_at', '=', Carbon::now()->month)
            ->sum('Price');

        // Calculate earnings for the last month
        $lastMonthEarnings = DB::table('orders')
            ->whereYear('created_at', '=', Carbon::now()->subMonth()->year)
            ->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)
            ->sum('Price');

        // Calculate percentage change
        $percentageChange = $lastMonthEarnings > 0
            ? (($currentMonthEarnings - $lastMonthEarnings) / $lastMonthEarnings) * 100
            : 0;

        // Get the top 3 best-selling brands
        $topBrands = DB::table('orders')
            ->join('products', 'orders.ProductID', '=', 'products.ProductID')
            ->join('brands', 'products.BrandID', '=', 'brands.BrandID')
            ->select('brands.BrandName', DB::raw('SUM(orders.Price) as total_sales'), DB::raw('COUNT(orders.ProductID) as total_sold'))
            ->groupBy('brands.BrandID', 'brands.BrandName')
            ->orderByDesc('total_sold')
            ->limit(3)
            ->get();

        return response()->json([
            'currentMonthEarnings' => $currentMonthEarnings,
            'lastMonthEarnings' => $lastMonthEarnings,
            'percentageChange' => number_format($percentageChange, 2),
            'topBrands' => $topBrands
        ]);
    }

    public function getWeeklyProfitComparison()
    {
        // Get the start and end of the current week
        $currentWeekStart = Carbon::now()->startOfWeek();
        $currentWeekEnd = Carbon::now()->endOfWeek();

        // Get the start and end of the previous week
        $lastWeekStart = Carbon::now()->subWeek()->startOfWeek();
        $lastWeekEnd = Carbon::now()->subWeek()->endOfWeek();

        // Calculate total sales for all time
        $totalSales = DB::table('orders')->sum('Price');

        // Calculate sales for the current week
        $currentWeekSales = DB::table('orders')
            ->whereBetween('created_at', [$currentWeekStart, $currentWeekEnd])
            ->sum('Price');

        // Calculate sales for the last week
        $lastWeekSales = DB::table('orders')
            ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])
            ->sum('Price');

        // Calculate percentage change
        $percentageChange = $lastWeekSales > 0
            ? (($currentWeekSales - $lastWeekSales) / $lastWeekSales) * 100
            : ($currentWeekSales > 0 ? 100 : 0);

        return response()->json([
            'totalSales' => number_format($totalSales, 2),  // 🔹 Added total sales
            'currentWeekSales' => number_format($currentWeekSales, 2),
            'lastWeekSales' => number_format($lastWeekSales, 2),
            'percentageChange' => number_format($percentageChange, 2),
        ]);
    }

    public function getNewOrderStats()
{
    $currentMonthOrders = Order::whereYear('created_at', Carbon::now()->year)
        ->whereMonth('created_at', Carbon::now()->month)
        ->count();

    $lastMonthOrders = Order::whereYear('created_at', Carbon::now()->subMonth()->year)
        ->whereMonth('created_at', Carbon::now()->subMonth()->month)
        ->count();

    $percentageChange = $lastMonthOrders > 0
        ? (($currentMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100
        : ($currentMonthOrders > 0 ? 100 : 0);

    return response()->json([
        'currentMonthOrders' => $currentMonthOrders,
        'lastMonthOrders' => $lastMonthOrders,
        'percentageChange' => number_format($percentageChange, 2)
    ]);
}


}
