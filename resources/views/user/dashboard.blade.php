{{-- resources/views/user/dashboard.blade.php --}}
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cluckory - User Dashboard</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@400;500;700;800;900&amp;family=Plus+Jakarta+Sans:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 font-['Plus_Jakarta_Sans']">

<!-- TopNavBar -->
<header class="bg-white flex justify-between items-center w-full px-6 py-4 sticky top-0 z-50 shadow-sm border-b border-gray-100">
    <div class="text-2xl font-black text-[#b90027] tracking-tighter italic">
        Cluckory
    </div>
    
    <div class="flex items-center space-x-4">
        <div class="relative group">
            <button class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-100">
                <span class="material-symbols-outlined text-gray-700">person</span>
            </button>
            
            <div class="absolute right-0 mt-2 w-44 bg-white rounded-xl shadow-lg border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50">
                <div class="px-4 py-3 border-b border-gray-100">
                    <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name ?? 'Customer' }}</p>
                    <p class="text-xs text-gray-500">{{ Auth::user()->email ?? 'customer@cluckory.com' }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">logout</span>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<main class="max-w-[1280px] mx-auto px-6 py-12" x-data="userDashboard()" x-init="init()">
    
    <!-- Hero Section -->
    <section class="mb-12">
        <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-3xl p-8 relative overflow-hidden">
            <div class="relative z-10">
                <h1 class="text-4xl md:text-5xl font-bold text-[#b90027] mb-3">HEY CLUCKERS! 🍗</h1>
                <p class="text-lg text-gray-700">Pilih menu favorit anda dibawah ini.</p>
            </div>
        </div>
    </section>

    <!-- Debug Info (tampilkan hanya jika tidak ada produk) -->
    <div x-show="products.length === 0 && !loading" class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
        <p class="text-yellow-800">⚠️ Debug: Tidak ada produk yang ditemukan. Pastikan database memiliki data menu.</p>
    </div>

    <!-- Loading -->
    <div x-show="loading" class="text-center py-20">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-4 border-[#b90027]"></div>
        <p class="mt-4 text-gray-500">Loading menu...</p>
    </div>

    <!-- Menu Grid -->
    <div x-show="!loading">
        <!-- Filter -->
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Our Menu</h2>
            <div class="flex gap-3">
                <button @click="filterActive = 'all'" 
                        :class="filterActive === 'all' ? 'bg-[#b90027] text-white' : 'bg-white border border-gray-300'"
                        class="px-5 py-2 rounded-xl font-semibold text-sm transition">
                    All Items
                </button>
                <button @click="filterActive = 'active'" 
                        :class="filterActive === 'active' ? 'bg-[#b90027] text-white' : 'bg-white border border-gray-300'"
                        class="px-5 py-2 rounded-xl font-semibold text-sm transition">
                    Available Only
                </button>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <template x-for="product in filteredProducts" :key="product.id">
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition group">
                    <!-- Image -->
                    <div class="relative h-56 w-full overflow-hidden bg-gray-100">
                        <img :src="product.image_url || 'https://placehold.co/400x300?text=Cluckory'" 
                             :alt="product.name" 
                             class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        <!-- Status Badge -->
                        <div x-show="product.is_active" class="absolute top-3 right-3 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-lg">
                            Available
                        </div>
                        <div x-show="!product.is_active" class="absolute top-3 right-3 bg-gray-800 text-white text-xs font-bold px-2 py-1 rounded-lg">
                            Not Available
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="p-5">
                        <h3 class="font-bold text-xl text-gray-900 mb-2" x-text="product.name"></h3>
                        <p class="text-gray-500 text-sm mb-4 line-clamp-2" x-text="product.description || 'No description'"></p>
                        
                        <div class="flex items-baseline gap-1 mb-4">
                            <span class="text-2xl font-bold text-[#b90027]" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(product.price)"></span>
                        </div>
                        
                        <!-- TOMBOL PESAN SEKARANG -->
                        <button @click="openOrderModal(product)" 
                                :disabled="!product.is_active"
                                :class="!product.is_active ? 'bg-gray-300 cursor-not-allowed opacity-50' : 'bg-[#e31837] hover:bg-[#b90027]'"
                                class="w-full text-white font-semibold py-3 rounded-xl transition flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[20px]">shopping_basket</span>
                            <span x-text="!product.is_active ? 'Out of Stock' : 'Pesan Sekarang'"></span>
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <!-- Empty State -->
        <div x-show="filteredProducts.length === 0" class="text-center py-20 bg-white rounded-2xl">
            <span class="material-symbols-outlined text-7xl text-gray-300">restaurant_menu</span>
            <p class="text-gray-400 mt-4">No menu items available</p>
        </div>
    </div>

    <!-- Order Popup Modal -->
    <div x-show="orderModalOpen" 
         class="fixed inset-0 bg-black/60 z-[60] flex items-center justify-center p-4"
         x-cloak
         @click.away="closeOrderModal()">
        
        <div class="bg-white rounded-3xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white p-6 border-b flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold">Lengkapi Pesanan</h3>
                    <p class="text-sm text-gray-500" x-text="selectedProduct?.name"></p>
                </div>
                <button @click="closeOrderModal()" class="text-gray-400 hover:text-gray-600">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <div class="p-6 space-y-6">
                <!-- Pilihan Saos -->
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Pilih Saos</label>
                    <p class="text-xs text-gray-500 mb-3" x-text="'Jatah saos: ' + quantity + ' (1 saos per item)'"></p>
                    <div class="space-y-2">
                        <template x-for="sauce in sauceOptions" :key="sauce">
                            <div class="flex items-center justify-between bg-gray-50 p-3 rounded-xl border border-gray-100">
                                <span class="font-semibold text-gray-700 text-sm" x-text="sauce"></span>
                                <div class="flex items-center gap-3">
                                    <button @click="decrementSauce(sauce)" 
                                            class="w-7 h-7 flex items-center justify-center bg-white rounded-full border border-gray-200 shadow-sm hover:bg-gray-50 transition">-</button>
                                    <span class="font-bold text-sm w-4 text-center" x-text="selectedSauceMap[sauce] || 0"></span>
                                    <button @click="incrementSauce(sauce)" 
                                            class="w-7 h-7 flex items-center justify-center bg-[#b90027] text-white rounded-full shadow-md hover:bg-[#a00022] transition">+</button>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="mt-3 flex justify-between items-center px-1">
                        <span class="text-xs font-bold" :class="totalSelectedSauces == quantity ? 'text-green-600' : 'text-orange-500'">
                            Terpilih: <span x-text="totalSelectedSauces"></span> / <span x-text="quantity"></span>
                        </span>
                        <template x-if="totalSelectedSauces < quantity">
                            <span class="text-[10px] text-orange-400 italic font-medium">Silahkan pilih saos lagi</span>
                        </template>
                    </div>
                </div>

                <!-- Jumlah -->
                <div>
                    <label class="block font-bold text-gray-700 mb-2">Jumlah</label>
                    <div class="flex items-center gap-4">
                        <button @click="if(quantity > 1) quantity--" class="w-10 h-10 bg-gray-100 rounded-full hover:bg-gray-200">-</button>
                        <span class="text-xl font-bold w-12 text-center" x-text="quantity"></span>
                        <button @click="quantity++" class="w-10 h-10 bg-gray-100 rounded-full hover:bg-gray-200">+</button>
                    </div>
                </div>

                <!-- Nomor Telepon -->
                <div>
                    <label class="block font-bold text-gray-700 mb-2">Nomor WhatsApp</label>
                    <input type="tel" x-model="phoneNumber" 
                           placeholder="08123456789"
                           class="w-full px-4 py-3 border rounded-xl focus:ring-[#b90027] focus:border-[#b90027]">
                </div>

                <!-- Alamat -->
                <div>
                    <label class="block font-bold text-gray-700 mb-2">Alamat Lengkap</label>
                    <textarea x-model="address" rows="3"
                              placeholder="Masukkan alamat lengkap Anda (Jalan, No Rumah, Blok, dll)"
                              class="w-full px-4 py-3 border rounded-xl focus:ring-[#b90027] focus:border-[#b90027]"></textarea>
                </div>

                <!-- Keterangan Tambahan -->
                <div>
                    <label class="block font-bold text-gray-700 mb-2">Keterangan (Opsional)</label>
                    <textarea x-model="extraNotes" rows="2"
                              placeholder="Contoh: Bikin garing, sambal dipisah, dll."
                              class="w-full px-4 py-3 border rounded-xl focus:ring-[#b90027] focus:border-[#b90027]"></textarea>
                </div>

                <!-- Total -->
                <div class="bg-gray-50 rounded-xl p-4">
                    <div class="flex justify-between items-center">
                        <span class="font-bold">Total</span>
                        <span class="font-bold text-[#b90027] text-xl" 
                              x-text="'Rp ' + new Intl.NumberFormat('id-ID').format((selectedProduct?.price || 0) * quantity)"></span>
                    </div>
                </div>
            </div>

            <div class="sticky bottom-0 bg-white p-6 border-t">
                <button @click="sendWhatsAppOrder()" 
                        class="w-full bg-[#25D366] hover:bg-[#128C7E] text-white font-bold py-4 rounded-2xl transition flex items-center justify-center gap-3">
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                    Pesan via WhatsApp
                </button>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div x-show="successModalOpen" 
         class="fixed inset-0 bg-black/60 z-[70] flex items-center justify-center p-4"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         @click.away="successModalOpen = false">
        
        <div class="bg-white rounded-3xl w-full max-w-sm p-8 text-center shadow-2xl">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <span class="material-symbols-outlined text-green-600 text-5xl">check_circle</span>
            </div>
            
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Pesanan Berhasil!</h3>
            <p class="text-gray-500 mb-8">Pesanan Anda telah diteruskan ke WhatsApp kami. Mohon tunggu konfirmasi dari tim kami.</p>
            
            <button @click="successModalOpen = false" 
                    class="w-full bg-[#b90027] hover:bg-[#a00022] text-white font-bold py-3 rounded-xl transition shadow-lg shadow-red-100">
                Siap, Cluckory! 🍗
            </button>
        </div>
    </div>

    <!-- Toast -->
    <div x-show="toast.show" 
         x-transition.duration.300ms
         class="fixed bottom-4 right-4 px-4 py-3 rounded-xl shadow-lg z-50"
         :class="toast.type === 'error' ? 'bg-red-500' : 'bg-green-500'"
         x-cloak>
        <span class="text-white" x-text="toast.message"></span>
    </div>
</main>

<script>
function userDashboard() {
    return {
        products: [],
        loading: true,
        filterActive: 'all',
        toast: { show: false, message: '', type: 'success' },
        
        // Order Modal
        orderModalOpen: false,
        successModalOpen: false,
        selectedProduct: null,
        selectedSauceMap: {},
        extraNotes: '',
        quantity: 1,
        userName: '{{ Auth::user()->name ?? "Customer" }}',
        phoneNumber: '',
        address: '',
        sauceOptions: ['Spicy BBQ', 'Cheese', 'Nanban', 'Mentai', 'Mayonese'],

        get totalSelectedSauces() {
            return Object.values(this.selectedSauceMap).reduce((a, b) => a + b, 0);
        },
        
        get filteredProducts() {
            if (this.filterActive === 'active') {
                return this.products.filter(p => p.is_active == 1 || p.is_active === true);
            }
            return this.products;
        },
        
        async init() {
            console.log('Init started');
            await this.fetchProducts();
            this.loading = false;
            console.log('Products final:', this.products);
        },
        
        async fetchProducts() {
            try {
                console.log('Fetching from /user/menu-items');
                const response = await fetch('/user/menu-items', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                console.log('Response status:', response.status);
                const data = await response.json();
                console.log('Response data:', data);
                
                if (data.success && data.data) {
                    this.products = data.data;
                } else if (Array.isArray(data)) {
                    this.products = data;
                } else {
                    this.products = [];
                    console.error('Unexpected data format:', data);
                }
                
                console.log('Products loaded:', this.products.length);
                
            } catch (error) {
                console.error('Fetch error:', error);
                this.products = [];
            }
        },
        
        openOrderModal(product) {
            console.log('Opening modal for:', product);
            
            if (!product.is_active) {
                this.showToast('Menu tidak tersedia', 'error');
                return;
            }
            
            this.selectedProduct = product;
            this.selectedSauceMap = {};
            this.sauceOptions.forEach(s => this.selectedSauceMap[s] = 0);
            this.extraNotes = '';
            this.quantity = 1;
            this.phoneNumber = '';
            this.address = '';
            this.orderModalOpen = true;
        },

        incrementSauce(sauce) {
            if (this.totalSelectedSauces < this.quantity) {
                this.selectedSauceMap[sauce]++;
            } else {
                this.showToast(`Maksimal ${this.quantity} saos untuk ${this.quantity} item`, 'error');
            }
        },

        decrementSauce(sauce) {
            if (this.selectedSauceMap[sauce] > 0) {
                this.selectedSauceMap[sauce]--;
            }
        },
        
        closeOrderModal() {
            this.orderModalOpen = false;
            this.selectedProduct = null;
        },
        
        async sendWhatsAppOrder() {
            if (this.totalSelectedSauces === 0) {
                this.showToast('Pilih saos dulu ya!', 'error');
                return;
            }
            if (this.totalSelectedSauces < this.quantity) {
                this.showToast(`Pilih ${this.quantity} saos (1 per item)`, 'error');
                return;
            }
            if (!this.phoneNumber) {
                this.showToast('Isi nomor WhatsApp dulu', 'error');
                return;
            }
            if (!this.address) {
                this.showToast('Isi alamat lengkap dulu', 'error');
                return;
            }
            
            const total = this.selectedProduct.price * this.quantity;
            
            // Format sauces text: "2 Cheese, 1 Spicy BBQ"
            const saucesText = Object.entries(this.selectedSauceMap)
                .filter(([name, qty]) => qty > 0)
                .map(([name, qty]) => `${qty} ${name}`)
                .join(', ');
            
            // Record order in database first for admin dashboard stats
            try {
                await fetch('/user/checkout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        items: [{ id: this.selectedProduct.id, quantity: this.quantity }],
                        total: total,
                        address: this.address,
                        sauce: saucesText,
                        phone: this.phoneNumber,
                        notes: this.extraNotes
                    })
                });
            } catch (error) {
                console.error('Failed to record order:', error);
            }
            
            const storeNumber = '6281383862288'; // Ganti dengan nomor toko
            
            let message = `*ORDER CLUCKORY* 🍗\n\n`;
            message += `Menu: ${this.selectedProduct.name}\n`;
            message += `Jumlah: ${this.quantity}x\n`;
            message += `Saos: ${saucesText}\n`;
            if (this.extraNotes) {
                message += `Catatan: ${this.extraNotes}\n`;
            }
            message += `Total: Rp ${total.toLocaleString('id-ID')}\n\n`;
            message += `*Data Diri:*\n`;
            message += `Nama: ${this.userName}\n`;
            message += `No WhatsApp: ${this.phoneNumber}\n`;
            message += `Alamat: ${this.address}\n\n`;
            message += `Mohon dikonfirmasi. Terima kasih! 🙏`;
            
            const url = `https://wa.me/${storeNumber}?text=${encodeURIComponent(message)}`;
            window.open(url, '_blank');
            
            this.closeOrderModal();
            this.successModalOpen = true;
        },
        
        showToast(message, type = 'success') {
            this.toast = { show: true, message, type };
            setTimeout(() => { this.toast.show = false; }, 3000);
        }
    }
}
</script>
</body>
</html>