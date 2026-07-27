{{-- resources/views/admin/dashboard.blade.php --}}
<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cluckory Admin Dashboard - {{ config('app.name', 'Cluckory') }}</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@400;500;600;700;800;900&amp;family=Plus+Jakarta+Sans:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#b90027",
                        secondary: "#725c00",
                        surface: "#f9f9f9",
                    },
                    fontFamily: {
                        'jakarta': ['Plus Jakarta Sans', 'sans-serif'],
                        'epilogue': ['Epilogue', 'sans-serif'],
                    }
                },
            },
        }
    </script>
    
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .sidebar-link.active {
            background-color: #b90027;
            color: white;
            box-shadow: 0 4px 6px -1px rgba(185, 0, 39, 0.1);
        }
        .sidebar-link.active .material-symbols-outlined {
            font-variation-settings: 'FILL' 1;
        }
    </style>
</head>
<body class="bg-gray-50 font-jakarta">

{{-- Sidebar --}}
<aside class="fixed left-0 top-0 h-full w-64 bg-white border-r border-gray-100 z-50">
    <div class="p-6 border-b border-gray-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center shadow-lg">
                <span class="material-symbols-outlined text-white text-xl">restaurant</span>
            </div>
            <div>
                <h1 class="text-xl font-bold text-primary font-epilogue">Cluckory</h1>
                <p class="text-[10px] font-semibold text-gray-400 tracking-wider uppercase">Admin Panel</p>
            </div>
        </div>
    </div>
    
    <nav class="p-4 space-y-1">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link active flex items-center gap-3 px-4 py-3 rounded-xl transition-all">
            <span class="material-symbols-outlined text-xl">dashboard</span>
            <span class="text-sm font-bold">Overview</span>
        </a>
        <a href="{{ route('admin.menus.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-primary rounded-xl transition-all">
            <span class="material-symbols-outlined text-xl">restaurant_menu</span>
            <span class="text-sm font-semibold">Menu Management</span>
        </a>
    </nav>
    
    <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-100">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-3 w-full px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-primary rounded-xl transition-all">
                <span class="material-symbols-outlined">logout</span>
                <span class="text-sm font-semibold">Logout</span>
            </button>
        </form>
    </div>
</aside>

{{-- Top Bar --}}
<header class="fixed top-0 right-0 left-64 bg-white/90 backdrop-blur-md border-b border-gray-100 z-40 h-16 px-6 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <nav class="text-sm">
            <ol class="flex items-center gap-2">
                <li class="text-gray-400 font-semibold">Dashboard</li>
                <li class="text-gray-300">/</li>
                <li class="text-primary font-bold">Overview</li>
            </ol>
        </nav>
        
        <!-- Current Date Badge -->
        <div class="flex items-center gap-2 px-3 py-1.5 bg-primary/5 rounded-lg border border-primary/10 ml-4">
            <span class="material-symbols-outlined text-primary text-sm">event</span>
            <span class="text-xs font-bold text-gray-700">
                {{ now()->format('l, d M Y') }}
            </span>
        </div>
    </div>
    
    <div class="flex items-center gap-4">
        <div class="flex items-center gap-3">
            <div class="text-right">
                <p class="text-sm font-bold text-gray-900">{{ Auth::user()->name ?? 'Admin Profile' }}</p>
                <p class="text-xs text-gray-400 font-semibold">{{ Auth::user()->role ?? 'Head Chef' }}</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-gray-200 border-2 border-primary/20 overflow-hidden">
                <img class="w-full h-full object-cover" src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?background=b90027&color=fff&name='.urlencode(Auth::user()->name ?? 'Admin') }}" alt="Avatar">
            </div>
        </div>
    </div>
</header>

{{-- Main Content --}}
<main class="ml-64 mt-16 p-6" x-data="adminDashboard()">
    
    <!-- Order Detail Modal -->
    <div x-show="showOrderModal" 
         class="fixed inset-0 bg-black/60 z-[60] flex items-center justify-center p-4"
         x-cloak
         @click.away="showOrderModal = false">
        
        <div class="bg-white rounded-3xl w-full max-w-lg overflow-hidden shadow-2xl">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">receipt_long</span>
                    Detail Pesanan <span x-text="'#' + selectedOrder?.order_number"></span>
                </h3>
                <button @click="showOrderModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <div class="p-8 space-y-6">
                <!-- Customer Info -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Pelanggan</p>
                        <p class="font-semibold text-gray-900" x-text="selectedOrder?.user?.name || 'Guest'"></p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Status</p>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold" 
                              :class="getStatusColor(selectedOrder?.status)"
                              x-text="selectedOrder?.status?.toUpperCase()"></span>
                    </div>
                </div>

                <!-- Order Content -->
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Ringkasan</p>
                    <div class="bg-gray-50 rounded-2xl p-4 space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Jumlah Menu</span>
                            <span class="text-sm font-bold text-gray-900" x-text="(selectedOrder?.items?.length || 0) + ' Items'"></span>
                        </div>
                        <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                            <span class="text-sm font-bold text-gray-900">Total Bayar</span>
                            <span class="text-lg font-bold text-primary" x-text="'Rp ' + formatPrice(selectedOrder?.total_amount)"></span>
                        </div>
                    </div>
                </div>

                <!-- Address & Notes -->
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Alamat Pengiriman</p>
                        <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-xl border border-gray-100" x-text="selectedOrder?.shipping_address || 'Tidak ada alamat'"></p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Catatan / Saos</p>
                        <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-xl border border-gray-100" x-text="selectedOrder?.notes || 'Tidak ada catatan'"></p>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-gray-50 border-t border-gray-100">
                <button @click="showOrderModal = false" 
                        class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-3 rounded-xl transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
    <div class="max-w-7xl mx-auto">
        {{-- Flash Message --}}
        @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-green-600">check_circle</span>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Orders -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative">
                <div class="absolute top-4 right-4 bg-green-50 px-2 py-1 rounded-lg">
                    <span class="text-xs font-bold text-green-600">{{ $orderGrowth ?? '+12%' }}</span>
                </div>
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Orders Today</p>
                        <h3 class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_orders'] ?? 0) }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary text-2xl">receipt_long</span>
                    </div>
                </div>
                <div class="h-1 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-primary rounded-full" style="width: {{ $orderProgress ?? 75 }}%"></div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative">
                <div class="absolute top-4 right-4 bg-orange-50 px-2 py-1 rounded-lg">
                    <span class="text-xs font-bold text-orange-600">{{ $revenueGrowth ?? '+8.4%' }}</span>
                </div>
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Revenue Today</p>
                        <h3 class="text-3xl font-bold text-gray-900">Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-secondary text-2xl">payments</span>
                    </div>
                </div>
                <div class="h-1 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-secondary rounded-full" style="width: {{ $revenueProgress ?? 62 }}%"></div>
                </div>
            </div>

            <!-- Total Customers -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative">
                <div class="absolute top-4 right-4 bg-blue-50 px-2 py-1 rounded-lg">
                    <span class="text-xs font-bold text-blue-600">{{ $customerGrowth ?? '+5%' }}</span>
                </div>
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Customers</p>
                        <h3 class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_customers'] ?? 0) }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-blue-600 text-2xl">group</span>
                    </div>
                </div>
                <div class="h-1 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-600 rounded-full" style="width: {{ $customerProgress ?? 48 }}%"></div>
                </div>
            </div>
        </div>

        {{-- Recent Orders Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Recent Orders</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Real-time update of latest sales activity</p>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Order ID</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Customer Name</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Menu Item</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Total Price</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($recent_orders as $order)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-gray-900">#{{ $order->order_number }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-600 font-medium">{{ $order->user->name ?? 'Guest' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @forelse($order->items ?? [] as $item)
                                <div class="flex items-center gap-2 mb-0.5 last:mb-0">
                                    <span class="text-sm font-bold text-gray-900">{{ $item['name'] ?? 'Menu Item' }}</span>
                                    <span class="text-xs text-gray-400 font-medium">x{{ $item['quantity'] ?? 0 }}</span>
                                </div>
                                @empty
                                <span class="text-xs text-gray-400 italic text-center block">No items recorded</span>
                                @endforelse
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-primary">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $status = ucfirst($order->status);
                                    $statusColor = match($order->status) {
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'processing' => 'bg-blue-100 text-blue-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                @endphp
                                <span class="px-3 py-1 text-xs font-bold rounded-full {{ $statusColor }}">
                                    {{ $status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($order->status === 'pending')
                                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" class="p-1.5 text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="Konfirmasi Pesanan">
                                            <span class="material-symbols-outlined text-lg">check_circle</span>
                                        </button>
                                    </form>
                                    @endif
                                    <button @click="viewOrder({{ $order->toJson() }})" 
                                            class="text-gray-400 hover:text-primary transition-colors">
                                        <span class="material-symbols-outlined text-lg">visibility</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                                <span class="material-symbols-outlined text-4xl mb-2">inbox</span>
                                <p>No orders found in database</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 bg-white border-t border-gray-100">
                {{ $recent_orders->links() }}
            </div>
        </div>
    </div>
</main>

<style>
    [x-cloak] { display: none !important; }
</style>

<script>
    function adminDashboard() {
        return {
            showOrderModal: false,
            selectedOrder: null,
            
            viewOrder(order) {
                console.log('Viewing order:', order);
                this.selectedOrder = order;
                this.showOrderModal = true;
            },
            
            formatPrice(price) {
                return new Intl.NumberFormat('id-ID').format(price || 0);
            },
            
            getStatusColor(status) {
                switch(status?.toLowerCase()) {
                    case 'pending': return 'bg-yellow-100 text-yellow-800';
                    case 'processing': return 'bg-blue-100 text-blue-800';
                    case 'completed': return 'bg-green-100 text-green-800';
                    case 'cancelled': return 'bg-red-100 text-red-800';
                    default: return 'bg-gray-100 text-gray-800';
                }
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide flash message
        const flashMessage = document.querySelector('.bg-green-50');
        if (flashMessage) {
            setTimeout(() => {
                flashMessage.style.opacity = '0';
                flashMessage.style.transition = 'opacity 0.3s ease';
                setTimeout(() => flashMessage.remove(), 300);
            }, 5000);
        }
        
        // Set active sidebar link based on current URL
        const currentPath = window.location.pathname;
        document.querySelectorAll('.sidebar-link').forEach(link => {
            const href = link.getAttribute('href');
            if (href && currentPath === href) {
                link.classList.add('active');
                link.classList.remove('text-gray-600');
            } else if (href !== currentPath) {
                link.classList.remove('active');
            }
        });
    });
</script>

</body>
</html>