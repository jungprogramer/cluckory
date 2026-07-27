<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Menu;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_orders' => Order::whereDate('created_at', today())->count(),
            'total_revenue' => Order::whereDate('created_at', today())->where('payment_status', 'paid')->sum('total_amount'),
            'total_customers' => User::where('role', 'user')->count(),
            'total_menu' => Menu::count(),
        ];
        
        $recent_orders = Order::with(['user' => function($query) {
            $query->withCount('orders');
        }])->latest()->paginate(5);
        
        return view('admin.dashboard', compact('stats', 'recent_orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string|in:pending,processing,completed,cancelled'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Order status updated successfully!');
    }
}