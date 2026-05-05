@extends('layouts.app')

@section('title', 'Manajemen Menu')

@section('header')
<header class="flex items-start justify-between bg-transparent pb-2 max-w-[1400px] mx-auto w-full mt-2">
    <!-- Left side -->
    <div>
        <div class="flex items-center text-[11px] font-semibold text-gray-400 tracking-wide uppercase mb-1">
            <span class="text-[#ea580c]">YORUCAFE</span>
            <i data-lucide="chevron-right" class="w-3 h-3 mx-1"></i>
            <span>Manajemen Menu</span>
        </div>
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
            Manajemen Menu
        </h2>
        <p class="text-xs font-medium text-gray-500 mt-1">Kelola semua item menu YoruCafe dengan mudah</p>
    </div>

    <!-- Right side -->
    <div class="flex items-center space-x-4" x-data>
        <!-- Notifications -->
        <button class="relative bg-white p-2.5 rounded-full text-gray-400 hover:text-gray-600 shadow-sm border border-gray-100 transition-all focus:outline-none">
            <span class="absolute top-2.5 right-2.5 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
            <i data-lucide="bell" class="w-5 h-5"></i>
        </button>

        <!-- User Profile Pic -->
        <a href="{{ route('profile') }}" class="h-10 w-10 rounded-full border-2 border-white shadow-sm overflow-hidden bg-gray-200 cursor-pointer mr-2 block hover:ring-2 hover:ring-[#ea580c] transition-all">
            <img src="{{ Auth::user()->image ? Storage::url(Auth::user()->image) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=ea580c&color=fff' }}" alt="User" class="w-full h-full object-cover">
        </a>

        <button @click="$dispatch('open-modal', 'create-menu')" class="bg-[#ea580c] hover:bg-[#c2410c] text-white px-5 py-2.5 rounded-xl font-bold text-sm flex items-center transition-colors shadow-sm">
            <i data-lucide="plus" class="w-4 h-4 mr-1.5"></i> Tambah Item Baru
        </button>
    </div>
</header>
@endsection

@section('content')
<div class="max-w-[1400px] mx-auto pb-8" x-data="{
        showCreateModal: false,
        showEditModal: false,
        showDeleteModal: false,
        selectedMenu: null,
        
        openEdit(menu) {
            this.selectedMenu = menu;
            this.showEditModal = true;
        },
        openDelete(menu) {
            this.selectedMenu = menu;
            this.showDeleteModal = true;
        }
    }" 
    @open-modal.window="if ($event.detail === 'create-menu') showCreateModal = true"
>
    
    <!-- Top Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <!-- Total Item -->
        <div class="bg-white rounded-[24px] p-5 shadow-sm border border-gray-100 flex flex-col justify-center">
            <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Total Item</p>
            <div class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ $totalItems }}</div>
        </div>

        <!-- Tersedia -->
        <div class="bg-white rounded-[24px] p-5 shadow-sm border border-gray-100 flex items-center">
            <div class="w-12 h-12 rounded-[14px] bg-green-50 flex items-center justify-center text-green-500 mr-4">
                <i data-lucide="check-circle" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Tersedia</p>
                <div class="text-2xl font-extrabold text-gray-900 tracking-tight">{{ $availableItems }}</div>
            </div>
        </div>

        <!-- Habis -->
        <div class="bg-white rounded-[24px] p-5 shadow-sm border border-gray-100 flex items-center">
            <div class="w-12 h-12 rounded-[14px] bg-red-50 flex items-center justify-center text-red-500 mr-4">
                <i data-lucide="x-circle" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Habis</p>
                <div class="text-2xl font-extrabold text-gray-900 tracking-tight">{{ $outOfStockItems }}</div>
            </div>
        </div>

        <!-- Kategori -->
        <div class="bg-white rounded-[24px] p-5 shadow-sm border border-gray-100 flex items-center">
            <div class="w-12 h-12 rounded-[14px] bg-orange-50 flex items-center justify-center text-orange-500 mr-4">
                <i data-lucide="tags" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Kategori</p>
                <div class="text-2xl font-extrabold text-gray-900 tracking-tight">{{ $totalCategories }}</div>
            </div>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center">
            <i data-lucide="check-circle" class="w-5 h-5 mr-2 text-green-500"></i>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
    @endif
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 flex items-start">
            <i data-lucide="alert-circle" class="w-5 h-5 mr-2 text-red-500 mt-0.5"></i>
            <div>
                <span class="font-bold text-sm">Terdapat kesalahan pada form:</span>
                <ul class="list-disc ml-5 text-sm mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Toolbar -->
    <div class="bg-white rounded-[20px] p-2 flex flex-col md:flex-row items-center justify-between shadow-sm border border-gray-100 mb-6 space-y-2 md:space-y-0">
        <!-- Search Form -->
        <form action="{{ route('menu') }}" method="GET" class="relative w-full md:w-1/3 flex-1 max-w-md ml-0 md:ml-2">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i data-lucide="search" class="h-4 w-4 text-gray-400"></i>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama menu..." class="block w-full pl-10 pr-3 py-2.5 border-transparent bg-gray-50 rounded-xl leading-5 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-[#ea580c] focus:border-transparent sm:text-sm font-medium transition-colors">
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
        </form>

        <!-- Filters & Actions -->
        <div class="flex items-center space-x-2 mr-0 md:mr-2 overflow-x-auto w-full md:w-auto">
            
            <!-- Filter Pills -->
            <div class="flex space-x-2 mr-2">
                <a href="{{ route('menu') }}" class="{{ !request('category') || request('category') == 'all' ? 'bg-[#ea580c] text-white' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }} px-4 py-2 rounded-xl text-sm font-bold shadow-sm transition-colors whitespace-nowrap">Semua</a>
                
                @foreach($categories as $category)
                <a href="{{ route('menu', ['category' => $category->slug] + request()->except('category')) }}" class="{{ request('category') == $category->slug ? 'bg-[#ea580c] text-white' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }} px-4 py-2 rounded-xl text-sm font-bold flex items-center transition-colors whitespace-nowrap">
                    @if($category->slug == 'makanan') <i data-lucide="utensils" class="w-3.5 h-3.5 mr-1.5"></i>
                    @elseif($category->slug == 'minuman') <i data-lucide="coffee" class="w-3.5 h-3.5 mr-1.5"></i>
                    @elseif($category->slug == 'paket') <i data-lucide="package" class="w-3.5 h-3.5 mr-1.5"></i>
                    @endif
                    {{ $category->name }}
                </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 overflow-hidden">
        
        <!-- Table Header Custom -->
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-white">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Daftar Menu</h3>
                <p class="text-xs text-gray-500 font-medium mt-0.5">Menampilkan {{ $menus->count() }} dari {{ $menus->total() }} item menu</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead>
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-[10px] font-extrabold text-gray-400 uppercase tracking-widest bg-gray-50/50">Foto</th>
                        <th scope="col" class="px-6 py-3 text-left text-[10px] font-extrabold text-gray-400 uppercase tracking-widest bg-gray-50/50">Nama Item</th>
                        <th scope="col" class="px-6 py-3 text-left text-[10px] font-extrabold text-gray-400 uppercase tracking-widest bg-gray-50/50">Kategori</th>
                        <th scope="col" class="px-6 py-3 text-left text-[10px] font-extrabold text-gray-400 uppercase tracking-widest bg-gray-50/50">Harga</th>
                        <th scope="col" class="px-6 py-3 text-left text-[10px] font-extrabold text-gray-400 uppercase tracking-widest bg-gray-50/50">Status Stok</th>
                        <th scope="col" class="px-6 py-3 text-right text-[10px] font-extrabold text-gray-400 uppercase tracking-widest bg-gray-50/50">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-50">
                    
                    @forelse($menus as $item)
                    <tr class="hover:bg-gray-50/50 transition-colors {{ !$item->is_available ? 'bg-gray-50/30' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="w-12 h-12 rounded-xl overflow-hidden bg-gray-100 border border-gray-200 shadow-sm {{ !$item->is_available ? 'opacity-60' : '' }}">
                                @if($item->image)
                                    @if(Str::startsWith($item->image, 'http'))
                                        <img src="{{ $item->image }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                                    @else
                                        <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                                    @endif
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <i data-lucide="image" class="w-5 h-5"></i>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-gray-900 {{ !$item->is_available ? 'opacity-60' : '' }}">{{ $item->name }}</div>
                            <div class="text-xs text-gray-500 font-medium mt-0.5 truncate max-w-[200px]">{{ $item->description }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-1 rounded-md text-[10px] font-extrabold text-orange-600 bg-orange-50 uppercase tracking-wider {{ !$item->is_available ? 'opacity-60' : '' }}">
                                {{ $item->category->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap {{ !$item->is_available ? 'opacity-60' : '' }}">
                            <div class="text-sm font-extrabold text-gray-900">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <form action="{{ route('menu.toggle', $item) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer" onchange="this.form.submit()" {{ $item->is_available ? 'checked' : '' }}>
                                        <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-500"></div>
                                    </label>
                                </form>
                                <span class="text-xs font-bold {{ $item->is_available ? 'text-green-600' : 'text-red-500' }}">
                                    {{ $item->is_available ? 'Tersedia' : 'Habis' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <button @click="openEdit({{ json_encode($item) }})" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 hover:bg-blue-100 flex items-center justify-center transition-colors">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </button>
                                <button @click="openDelete({{ json_encode($item) }})" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 flex items-center justify-center transition-colors">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">
                            Tidak ada menu yang ditemukan.
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $menus->links('pagination::tailwind') }}
        </div>
    </div>

    <!-- Create Modal -->
    <div x-show="showCreateModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="showCreateModal" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showCreateModal = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <!-- Modal panel -->
            <div x-show="showCreateModal" x-transition class="relative z-10 inline-block align-bottom bg-white rounded-[24px] text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form action="{{ route('menu.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4" id="modal-title">Tambah Menu Baru</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Nama Menu</label>
                                <input type="text" name="name" required class="mt-1 block w-full border border-gray-300 rounded-xl px-3 py-2 shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Kategori</label>
                                <select name="category_id" required class="mt-1 block w-full border border-gray-300 rounded-xl px-3 py-2 shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Harga (Rp)</label>
                                <input type="number" name="price" required min="0" class="mt-1 block w-full border border-gray-300 rounded-xl px-3 py-2 shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Deskripsi</label>
                                <textarea name="description" rows="3" class="mt-1 block w-full border border-gray-300 rounded-xl px-3 py-2 shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Foto (File)</label>
                                <input type="file" name="image" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                            </div>
                            <div class="text-center text-xs text-gray-400 font-bold">- ATAU -</div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Link Foto (URL)</label>
                                <input type="url" name="image_url" placeholder="https://..." class="mt-1 block w-full border border-gray-300 rounded-xl px-3 py-2 shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="is_available" id="create_is_available" checked class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                                <label for="create_is_available" class="ml-2 block text-sm text-gray-900 font-bold">Tersedia (Ready Stock)</label>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-[#ea580c] text-base font-bold text-white hover:bg-[#c2410c] focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                        <button type="button" @click="showCreateModal = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showEditModal" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showEditModal = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showEditModal" x-transition class="relative z-10 inline-block align-bottom bg-white rounded-[24px] text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <!-- Using a form action that gets updated by Alpine isn't trivial with Blade routes, so we use a dynamic action via alpine -->
                <form :action="`/menu/${selectedMenu?.id}`" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4" id="modal-title">Edit Menu</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Nama Menu</label>
                                <input type="text" name="name" x-model="selectedMenu.name" required class="mt-1 block w-full border border-gray-300 rounded-xl px-3 py-2 shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Kategori</label>
                                <select name="category_id" x-model="selectedMenu.category_id" required class="mt-1 block w-full border border-gray-300 rounded-xl px-3 py-2 shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Harga (Rp)</label>
                                <input type="number" name="price" x-model="selectedMenu.price" required min="0" class="mt-1 block w-full border border-gray-300 rounded-xl px-3 py-2 shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Deskripsi</label>
                                <textarea name="description" x-model="selectedMenu.description" rows="3" class="mt-1 block w-full border border-gray-300 rounded-xl px-3 py-2 shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Update Foto (File Opsional)</label>
                                <input type="file" name="image" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                            <div class="text-center text-xs text-gray-400 font-bold">- ATAU -</div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Update Link Foto (URL)</label>
                                <input type="url" name="image_url" placeholder="https://..." class="mt-1 block w-full border border-gray-300 rounded-xl px-3 py-2 shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="is_available" id="edit_is_available" x-bind:checked="selectedMenu.is_available" class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                                <label for="edit_is_available" class="ml-2 block text-sm text-gray-900 font-bold">Tersedia (Ready Stock)</label>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-bold text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Update</button>
                        <button type="button" @click="showEditModal = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div x-show="showDeleteModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showDeleteModal" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showDeleteModal = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showDeleteModal" x-transition class="relative z-10 inline-block align-bottom bg-white rounded-[24px] text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full">
                <form :action="`/menu/${selectedMenu?.id}`" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                            <i data-lucide="alert-triangle" class="h-6 w-6 text-red-600"></i>
                        </div>
                        <h3 class="text-lg leading-6 font-bold text-gray-900 mb-2" id="modal-title">Hapus Menu</h3>
                        <p class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus <span class="font-bold text-gray-900" x-text="selectedMenu?.name"></span>? Data ini tidak dapat dikembalikan.</p>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-bold text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Hapus</button>
                        <button type="button" @click="showDeleteModal = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
