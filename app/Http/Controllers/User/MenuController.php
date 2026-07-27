<?php
// app/Http/Controllers/User/MenuController.php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MenuController extends Controller
{
    public function getActiveMenus()
    {
        try {
            // Ambil semua data dari tabel menus
            $menus = Menu::orderBy('name', 'asc')->get();
            
            return response()->json([
                'success' => true,
                'data' => $menus,
                'total' => $menus->count(),
                'message' => 'Menus retrieved successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching menus: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Failed to fetch menu items: ' . $e->getMessage()
            ], 500);
        }
    }
}