<?php
// app/Http/Controllers/User/DashboardController.php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Kirim data menu yang active ke view (fallback jika JavaScript tidak jalan)
        $products = Menu::orderBy('name', 'asc')->get();
        
        return view('user.dashboard', compact('products'));
    }
    
    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'total' => 'required|numeric|min:0',
            'address' => 'nullable|string',
            'sauce' => 'nullable|string',
            'phone' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);
        
        try {
            DB::beginTransaction();
            
            // Generate order number
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());

            // Enrich items with names and prices for better reporting
            $enrichedItems = [];
            foreach ($request->items as $item) {
                $menu = Menu::find($item['id']);
                if ($menu) {
                    $enrichedItems[] = [
                        'id' => $item['id'],
                        'name' => $menu->name,
                        'quantity' => (int) $item['quantity'],
                        'price' => (float) $menu->price
                    ];
                }
            }
            
            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => $orderNumber,
                'total_amount' => $request->total,
                'status' => 'pending',
                'payment_status' => 'paid',
                'shipping_address' => $request->address,
                'items' => $enrichedItems,
                'notes' => 'Sauce: ' . $request->sauce . ' | Extra: ' . $request->notes . ' | Phone: ' . $request->phone,
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Order recorded successfully!',
                'order_number' => $orderNumber
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to record order.',
                'error' => $e->getMessage()
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