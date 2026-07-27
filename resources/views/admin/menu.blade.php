{{-- resources/views/admin/menu.blade.php --}}
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cluckory Admin - Menu Management</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        .modal-transition {
            transition: opacity 0.3s ease;
        }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: { 
                extend: { 
                    colors: { 
                        "primary": "#b90027", 
                        "primary-container": "#e31837", 
                        "on-surface": "#1a1c1c", 
                        "on-surface-variant": "#5d3f3e" 
                    } 
                } 
            }
        }
    </script>
</head>
<body class="bg-gray-50 text-gray-900 antialiased font-['Plus_Jakarta_Sans']">

<!-- Sidebar -->
<aside class="fixed left-0 top-0 h-full w-64 z-50 flex flex-col bg-white border-r border-gray-100">
    <div class="px-6 py-8 flex items-center gap-3">
        <h1 class="text-2xl font-black text-[#b90027] tracking-tight leading-none">Cluckory</h1>
    </div>
    <div class="px-6 pb-6">
        <p class="text-[10px] font-bold text-gray-400 tracking-wider uppercase">Admin Portal</p>
    </div>
    
    <nav class="flex-1 px-4 mt-2 space-y-1">
        <a class="flex items-center gap-3 text-gray-500 font-semibold rounded-xl px-4 py-3 hover:bg-gray-50 transition-all group" href="{{ route('admin.dashboard') }}">
            <span class="material-symbols-outlined text-xl group-hover:text-gray-600 transition-colors">grid_view</span>
            <span class="text-sm group-hover:text-gray-600 transition-colors">Overview</span>
        </a>
        <a class="flex items-center gap-3 bg-[#fdf2f4] text-[#b90027] font-semibold rounded-xl px-4 py-3 transition-all" href="{{ route('admin.menus.index') }}">
            <span class="material-symbols-outlined text-xl">restaurant_menu</span>
            <span class="text-sm">Menu Management</span>
        </a>
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-100">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-3 w-full px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-primary rounded-xl transition-all">
                <span class="material-symbols-outlined">logout</span>
                <span class="text-sm font-semibold">Logout</span>
            </button>
        </form>
    </div>
    </nav>
</aside>

<!-- Top Bar -->
<header class="fixed top-0 right-0 z-40 flex justify-between items-center px-10 bg-white border-b border-gray-100 ml-64 w-[calc(100%-16rem)] h-20">
    <div class="flex items-center">
        <h2 class="text-lg font-black text-[#b90027]">Cluckory Admin</h2>
    </div>
    
    <div class="flex items-center gap-4">
        <div class="text-right">
            <p class="text-sm font-bold text-gray-900 leading-tight">{{ Auth::user()->name ?? 'John Doe' }}</p>
            <p class="text-[9px] font-bold text-gray-400 tracking-wider uppercase">{{ Auth::user()->role ?? 'HEAD OF OPERATIONS' }}</p>
        </div>
        <img alt="Admin avatar" class="w-10 h-10 rounded-full object-cover border border-gray-200" src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?background=333&color=fff&name='.urlencode(Auth::user()->name ?? 'John') }}">
    </div>
</header>

<!-- Main Content -->
<main class="ml-64 pt-20 min-h-screen bg-gray-50 p-10" x-data="menuManager()" x-init="init()" x-cloak>
    
    <!-- Header & Add Button -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Menu Management</h1>
            <p class="text-[13px] font-medium text-gray-500">Review and manage your active restaurant offerings.</p>
        </div>
        <button @click="openModal()" class="bg-[#b90027] hover:bg-red-800 text-white font-semibold px-5 py-2.5 rounded-lg flex items-center gap-2 transition-all shadow-sm">
            <span class="material-symbols-outlined text-[18px]">add</span> Add New Menu
        </button>
    </div>

    <!-- Alert Messages -->
    <div x-show="showAlert" x-transition.duration.300ms class="mb-6" x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show">
        <div class="px-4 py-3 rounded-lg text-sm font-semibold" :class="alertType === 'success' ? 'bg-green-50 border border-green-200 text-green-700' : 'bg-red-50 border border-red-200 text-red-700'">
            <span x-text="alertMessage"></span>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm font-semibold" role="alert">
        {{ session('success') }}
    </div>
    @endif

    <!-- Loading Spinner -->
    <div x-show="loading" class="flex justify-center items-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#b90027]"></div>
    </div>

    <!-- Table Container -->
    <div x-show="!loading" class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="px-8 py-5 text-[10px] font-bold text-gray-500 uppercase tracking-widest whitespace-nowrap">Menu Item</th>
                    <th class="px-8 py-5 text-[10px] font-bold text-gray-500 uppercase tracking-widest whitespace-nowrap">Price</th>
                    <th class="px-8 py-5 text-[10px] font-bold text-gray-500 uppercase tracking-widest whitespace-nowrap text-center">Status</th>
                    <th class="px-8 py-5 text-[10px] font-bold text-gray-500 uppercase tracking-widest text-center whitespace-nowrap">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <template x-for="item in menus" :key="item.id">
                    <tr class="hover:bg-gray-50/30 transition-colors">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-5">
                                <div class="w-16 h-16 rounded-2xl overflow-hidden flex-shrink-0 bg-gray-50 shadow-sm border border-gray-100">
                                    <img :src="item.image_url || 'https://placehold.co/400x400?text=No+Image'" class="w-full h-full object-cover" alt="menu image">
                                </div>
                                <div class="flex flex-col gap-0.5">
                                    <p class="font-bold text-gray-900 text-sm" x-text="item.name"></p>
                                    <p class="text-[12px] font-medium text-gray-400" x-text="item.description || 'No description provided'"></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-sm font-bold text-[#b90027]" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(item.price)"></span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center justify-center gap-3">
                                <button type="button" 
                                    @click="toggleStatus(item.id, item.is_active)"
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                    :class="item.is_active ? 'bg-[#b90027]' : 'bg-gray-300'"
                                    role="switch">
                                    <span 
                                        aria-hidden="true" 
                                        class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                        :class="item.is_active ? 'translate-x-5' : 'translate-x-0'">
                                    </span>
                                </button>
                                <span class="text-[10px] font-bold tracking-wider uppercase" 
                                    :class="item.is_active ? 'text-green-600' : 'text-gray-400'"
                                    x-text="item.is_active ? 'ACTIVE' : 'INACTIVE'">
                                </span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center justify-center gap-3">
                                <button @click="openModal(item)" class="text-blue-600 hover:text-blue-800 transition-colors p-1 hover:bg-blue-50 rounded-lg" title="Edit">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                </button>
                                <button @click="deleteItem(item.id)" class="text-red-600 hover:text-red-800 transition-colors p-1 hover:bg-red-50 rounded-lg" title="Delete">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>
                <tr x-show="menus.length === 0">
                    <td colspan="4" class="text-center py-16 text-gray-400 text-sm font-medium">
                        No menu items found. Click "Add New Menu" to create one.
                    </td>
                </tr>
            </tbody>
        </table>
        
        <!-- Pagination -->
        <div x-show="lastPage > 1" class="px-8 py-6 flex items-center justify-between border-t border-gray-50">
            <div class="text-sm text-gray-500">
                Showing page <span x-text="currentPage"></span> of <span x-text="lastPage"></span>
            </div>
            <div class="flex gap-1">
                <button @click="fetchMenus(currentPage - 1)" :disabled="currentPage === 1" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:border-gray-300 disabled:opacity-50 disabled:cursor-not-allowed transition-colors text-sm">
                    ‹
                </button>
                <template x-for="page in Math.min(5, lastPage)" :key="page">
                    <button @click="fetchMenus(page)" class="w-8 h-8 flex items-center justify-center rounded-lg text-sm font-semibold transition-colors" 
                        :class="currentPage === page ? 'bg-[#b90027] text-white shadow-sm' : 'text-gray-600 hover:bg-gray-50'" 
                        x-text="page">
                    </button>
                </template>
                <button @click="fetchMenus(currentPage + 1)" :disabled="currentPage === lastPage" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:border-gray-300 disabled:opacity-50 disabled:cursor-not-allowed transition-colors text-sm">
                    ›
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Form (Add/Edit) -->
    <div x-show="isModalOpen" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 modal-transition" style="display: none;" @click.away="closeModal()">
        <div class="bg-white rounded-2xl w-full max-w-md shadow-xl overflow-hidden transform transition-all">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="text-lg font-bold text-gray-900" x-text="modalTitle"></h3>
                <button @click="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form @submit.prevent="submitForm" enctype="multipart/form-data" class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-2">Item Name *</label>
                        <input type="text" x-model="form.name" required
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-[#b90027] focus:ring-1 focus:ring-[#b90027] transition-colors outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-2">Description</label>
                        <textarea x-model="form.description" 
                                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-[#b90027] focus:ring-1 focus:ring-[#b90027] transition-colors outline-none resize-none" 
                                  rows="3"></textarea>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-2">Price (Rp) *</label>
                        <input type="number" step="1000" x-model="form.price" required
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-[#b90027] focus:ring-1 focus:ring-[#b90027] transition-colors outline-none">
                    </div>
                    
                    <!-- Image Upload -->
                    <div>
                        <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-2">Menu Image</label>
                        
                        <!-- Preview Image -->
                        <div x-show="imagePreview" class="mb-3">
                            <img :src="imagePreview" class="w-24 h-24 rounded-lg object-cover border border-gray-200" alt="Preview">
                        </div>
                        
                        <!-- File Input -->
                        <input type="file" 
                               @change="handleFileUpload($event)"
                               accept="image/jpeg,image/png,image/jpg,image/gif"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-[#b90027] focus:ring-1 focus:ring-[#b90027] transition-colors outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#b90027] file:text-white hover:file:bg-red-800">
                        <p class="text-xs text-gray-400 mt-1">Supported formats: JPG, PNG, GIF. Max 2MB</p>
                        <p x-show="imageError" class="text-xs text-red-500 mt-1" x-text="imageError"></p>
                    </div>
                    
                    <div class="flex items-center justify-between pt-2">
                        <label class="text-sm font-semibold text-gray-700">Active (Available for sale)</label>
                        <button type="button" 
                            @click="form.is_active = !form.is_active"
                            class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                            :class="form.is_active ? 'bg-[#b90027]' : 'bg-gray-300'"
                            role="switch">
                            <span 
                                aria-hidden="true" 
                                class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                :class="form.is_active ? 'translate-x-4' : 'translate-x-0'">
                            </span>
                        </button>
                    </div>
                </div>
                <div class="flex gap-3 mt-8">
                    <button type="button" @click="closeModal()" class="flex-1 bg-white border border-gray-200 text-gray-700 py-2.5 rounded-xl font-bold hover:bg-gray-50 transition-colors text-sm">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 bg-[#b90027] text-white py-2.5 rounded-xl font-bold hover:bg-red-800 transition-colors text-sm" :disabled="submitting">
                        <span x-show="!submitting">Save Menu Item</span>
                        <span x-show="submitting" class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Saving...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
function menuManager() {
    return {
        menus: [],
        isModalOpen: false,
        modalTitle: 'Add Menu Item',
        editingId: null,
        currentPage: 1,
        lastPage: 1,
        loading: false,
        submitting: false,
        showAlert: false,
        alertMessage: '',
        alertType: 'success',
        imageFile: null,
        imagePreview: null,
        imageError: '',
        form: {
            name: '',
            description: '',
            price: '',
            image_url: '',
            is_active: true
        },
        
        async init() {
            await this.fetchMenus();
        },
        
        showMessage(message, type = 'success') {
            this.alertMessage = message;
            this.alertType = type;
            this.showAlert = true;
            setTimeout(() => {
                this.showAlert = false;
            }, 3000);
        },
        
        handleFileUpload(event) {
            const file = event.target.files[0];
            this.imageError = '';
            
            if (!file) {
                this.imageFile = null;
                this.imagePreview = null;
                return;
            }
            
            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                this.imageError = 'Please upload only JPG, PNG, or GIF files';
                this.imageFile = null;
                this.imagePreview = null;
                return;
            }
            
            // Validate file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                this.imageError = 'File size must be less than 2MB';
                this.imageFile = null;
                this.imagePreview = null;
                return;
            }
            
            this.imageFile = file;
            
            // Create preview
            const reader = new FileReader();
            reader.onload = (e) => {
                this.imagePreview = e.target.result;
            };
            reader.readAsDataURL(file);
        },
        
        async fetchMenus(page = 1) {
            this.loading = true;
            try {
                const res = await fetch(`/admin/menus?page=${page}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (res.ok) {
                    const data = await res.json();
                    this.menus = data.data || [];
                    this.currentPage = data.current_page || 1;
                    this.lastPage = data.last_page || 1;
                } else {
                    console.error('Failed to fetch menus');
                }
            } catch (error) {
                console.error('Error fetching menus:', error);
            } finally {
                this.loading = false;
            }
        },
        
        openModal(menu = null) {
            this.imageFile = null;
            this.imagePreview = null;
            this.imageError = '';
            
            if (menu) {
                this.modalTitle = 'Edit Menu Item';
                this.editingId = menu.id;
                this.form = { 
                    name: menu.name,
                    description: menu.description || '',
                    price: menu.price,
                    image_url: menu.image_url || '',
                    is_active: menu.is_active
                };
                if (menu.image_url) {
                    this.imagePreview = menu.image_url;
                }
            } else {
                this.modalTitle = 'Add New Menu';
                this.editingId = null;
                this.form = { 
                    name: '', 
                    description: '', 
                    price: '', 
                    image_url: '', 
                    is_active: true 
                };
            }
            this.isModalOpen = true;
        },
        
        closeModal() {
            this.isModalOpen = false;
            this.submitting = false;
            this.imageFile = null;
            this.imagePreview = null;
            this.imageError = '';
        },
        
        async submitForm() {
            this.submitting = true;
            
            const formData = new FormData();
            formData.append('name', this.form.name);
            formData.append('description', this.form.description);
            formData.append('price', this.form.price);
            formData.append('is_active', this.form.is_active ? 1 : 0);
            
            if (this.imageFile) {
                formData.append('image', this.imageFile);
            }
            
            let url = '/admin/menus';
            let method = 'POST';
            
            if (this.editingId) {
                url = `/admin/menus/${this.editingId}`;
                formData.append('_method', 'PUT');
            }
            
            try {
                const res = await fetch(url, {
                    method: 'POST', // Use POST for both, with _method for PUT
                    headers: { 
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                if (res.ok) {
                    const data = await res.json();
                    this.closeModal();
                    await this.fetchMenus(this.currentPage);
                    this.showMessage(data.message || (this.editingId ? 'Menu updated successfully!' : 'Menu created successfully!'), 'success');
                } else {
                    const error = await res.json();
                    this.showMessage(error.message || 'Error saving data', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                this.showMessage('Network error. Please try again.', 'error');
            } finally {
                this.submitting = false;
            }
        },
        
        async toggleStatus(id, currentStatus) {
            const index = this.menus.findIndex(m => m.id === id);
            const originalStatus = currentStatus;
            
            // Optimistic update
            if (index !== -1) {
                this.menus[index].is_active = !currentStatus;
            }
            
            try {
                const res = await fetch(`/admin/menus/${id}/toggle-status`, {
                    method: 'PATCH',
                    headers: { 
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 
                        'Content-Type': 'application/json', 
                        'Accept': 'application/json' 
                    },
                    body: JSON.stringify({ is_active: !currentStatus })
                });
                
                if (!res.ok) {
                    throw new Error('Failed to update status');
                }
                
                const data = await res.json();
                this.showMessage(`Menu ${data.is_active ? 'activated' : 'deactivated'} successfully`, 'success');
            } catch (e) {
                // Revert on failure
                if (index !== -1) {
                    this.menus[index].is_active = originalStatus;
                }
                this.showMessage('Failed to update status', 'error');
            }
        },
        
        async deleteItem(id) {
            if (!confirm('Are you sure you want to delete this menu item? This action cannot be undone.')) {
                return;
            }
            
            try {
                const res = await fetch(`/admin/menus/${id}`, { 
                    method: 'DELETE', 
                    headers: { 
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 
                        'Accept': 'application/json' 
                    } 
                });
                
                if (res.ok) {
                    const data = await res.json();
                    await this.fetchMenus(this.currentPage);
                    this.showMessage(data.message || 'Menu deleted successfully!', 'success');
                } else {
                    this.showMessage('Failed to delete menu item', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                this.showMessage('Network error. Please try again.', 'error');
            }
        }
    }
}
</script>
</body>
</html>