<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Inquiries;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class Analytics extends Controller
{
  public function index()
  {
    $monthlySales = Order::select(
      DB::raw("DATE_FORMAT(created_at, '%M') as month"),
      DB::raw("SUM(Price) as total_sales")
      )->groupBy('month')->orderByRaw("STR_TO_DATE(month, '%M') ASC")->get();


     // Count total customers
     $totalCustomers = Customer::count();

     // Count total inquiries
     $totalInquiries = Inquiries::count();

     // Count total orders
     $totalOrders = Order::count();

     // Count total products in inventory
     $totalProducts = Product::count();

     // Calculate total sales based on the 'Price' column in 'orders' table
     $totalSales = Order::sum('Price');

     // Sales statistics grouped by date
     $salesData = Order::select(
         DB::raw("DATE(created_at) as date"),
         DB::raw("SUM(Price) as total_sales")
     )->groupBy('date')->orderBy('date', 'ASC')->get();

     return view('content.dashboard.dashboards-analytics', compact(
         'totalCustomers',
         'totalInquiries',
         'monthlySales',
         'totalOrders',
         'totalProducts',
         'totalSales',
         'salesData'
     ));
  }
}