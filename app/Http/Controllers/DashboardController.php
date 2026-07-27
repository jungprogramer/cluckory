<?php
// app/Http/Controllers/User/DashboardController.php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        // Data dari tabel menus untuk fallback
        $products = Menu::orderBy('name', 'asc')->get();
        
        return view('user.dashboard', compact('products'));
    }
    
    public function checkout(Request $request)
    {
        try {
            $request->validate([
                'items' => 'required|array',
                'total' => 'required|numeric|min:0'
            ]);
            
            DB::beginTransaction();
            
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
            
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => $orderNumber,
                'total_amount' => $request->total,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'items' => json_encode($request->items),
                'notes' => 'Order from web application'
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'order' => $order,
                'order_number' => $orderNumber
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to place order: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('user.orders', compact('orders'));
    }
}