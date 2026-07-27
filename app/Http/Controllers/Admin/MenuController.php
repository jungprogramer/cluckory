<?php
// app/Http/Controllers/Admin/MenuController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::orderBy('created_at', 'desc')->paginate(10);
        
        if (request()->wantsJson()) {
            return response()->json($menus);
        }
        
        return view('admin.menu', compact('menus'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'is_active' => 'boolean',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('menu-images', 'public');
                $validated['image_url'] = Storage::url($path);
            }

            $validated['is_active'] = $request->has('is_active') ? $request->is_active : true;
            
            $menu = Menu::create($validated);

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Menu created successfully!', 'menu' => $menu], 201);
            }

            return redirect()->route('admin.menus.index')->with('success', 'Menu created successfully!');
            
        } catch (\Exception $e) {
            Log::error('Error creating menu: ' . $e->getMessage());
            return response()->json(['message' => 'Error creating menu: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, Menu $menu)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'is_active' => 'boolean',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($request->hasFile('image')) {
                if ($menu->image_url) {
                    $oldPath = str_replace('/storage/', '', $menu->image_url);
                    Storage::disk('public')->delete($oldPath);
                }
                
                $path = $request->file('image')->store('menu-images', 'public');
                $validated['image_url'] = Storage::url($path);
            }

            $validated['is_active'] = $request->has('is_active') ? $request->is_active : false;
            
            $menu->update($validated);

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Menu updated successfully!', 'menu' => $menu]);
            }

            return redirect()->route('admin.menus.index')->with('success', 'Menu updated successfully!');
            
        } catch (\Exception $e) {
            Log::error('Error updating menu: ' . $e->getMessage());
            return response()->json(['message' => 'Error updating menu: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Menu $menu)
    {
        try {
            if ($menu->image_url) {
                $path = str_replace('/storage/', '', $menu->image_url);
                Storage::disk('public')->delete($path);
            }
            
            $menu->delete();

            if (request()->wantsJson()) {
                return response()->json(['message' => 'Menu deleted successfully!']);
            }

            return redirect()->route('admin.menus.index')->with('success', 'Menu deleted successfully!');
            
        } catch (\Exception $e) {
            Log::error('Error deleting menu: ' . $e->getMessage());
            return response()->json(['message' => 'Error deleting menu: ' . $e->getMessage()], 500);
        }
    }

    public function toggleStatus(Menu $menu)
    {
        try {
            $menu->is_active = !$menu->is_active;
            $menu->save();

            return response()->json([
                'success' => true,
                'is_active' => $menu->is_active,
                'message' => $menu->is_active ? 'Menu activated' : 'Menu deactivated'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle status: ' . $e->getMessage()
            ], 500);
        }
    }
}