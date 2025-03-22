<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class OrderListController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();

        // Join customers table to get the Customer Name
        $query->join('customers', 'orders.CustomerID', '=', 'customers.CustomerID')
            ->select('orders.*', 'customers.FullName as CustomerName');

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('orders.ReferenceNo', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('orders.OrderID', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('customers.FullName', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $orders = $query->paginate($request->per_page ?? 10)->appends([
            'search' => $request->search,
            'per_page' => $request->per_page
        ]);

        return view('content.pages.order-list', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'DeliveryStatus' => 'required|string|in:Order Placed,In Process,Delivered,Complete'
        ]);

        $order = Order::findOrFail($id);
        $order->DeliveryStatus = $request->DeliveryStatus;
        $order->save();

        return response()->json(['message' => 'Status updated successfully!', 'order' => $order]);
    }

    public function getPaymentStatus($orderId)
    {
        $paymentExists = Payment::where('OrderID', $orderId)->exists();
        return response()->json(['paymentStatus' => $paymentExists ? 2 : 1]);
    }

    public function destroy($id)
    {
        Order::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Order deleted successfully.']);
    }
}