<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Data metrics
        $totalOrders = Order::count();
        $totalRevenue = Order::sum('total_price');
        $totalCustomers = User::where('role', 'customer')->count();
        
        // Growth percentages (bisa dihitung dari periode sebelumnya)
        $orderGrowth = '+12%';
        $revenueGrowth = '+8.4%';
        $customerGrowth = '+5%';
        
        // Progress untuk progress bar
        $orderProgress = 75;
        $revenueProgress = 62;
        $customerProgress = 48;
        
        // Recent orders
        $recentOrders = Order::with('customer')
            ->latest()
            ->take(5)
            ->get()
            ->map(function($order) {
                return (object) [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer_name' => $order->customer->name ?? 'Guest',
                    'menu_item' => $order->items->first()->menu_name ?? 'N/A',
                    'total_price' => $order->total_price,
                    'status' => $order->status,
                ];
            });
        
        return view('admin.dashboard', compact(
            'totalOrders', 'totalRevenue', 'totalCustomers',
            'orderGrowth', 'revenueGrowth', 'customerGrowth',
            'orderProgress', 'revenueProgress', 'customerProgress',
            'recentOrders'
        ));
    }
}