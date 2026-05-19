@extends('layouts.pos')

@section('content')
<div x-data="posApp()" class="flex h-screen bg-[#eceeef] font-sans overflow-hidden">
    
    <!-- Left Area (Main) -->
    <div class="flex-1 flex flex-col relative px-8 pt-8 h-full overflow-hidden">
        
        <!-- Top Nav -->
        <div class="flex justify-between items-start mb-12 relative z-10 shrink-0">
            <!-- Operator Badge -->
            <div class="bg-white rounded-full p-2 pr-6 flex items-center shadow-sm relative">
                <div class="w-10 h-10 rounded-full overflow-hidden mr-3">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Operator') }}&background=2d3748&color=fff" class="w-full h-full object-cover">
                </div>
                <div>
                    <div class="flex items-center text-[10px] text-gray-500 font-bold mb-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span> Operator Active
                    </div>
                    <div class="font-extrabold text-gray-900 leading-none text-base">YoruCafe POS</div>
                </div>
                
                <!-- Logout Button -->
                <form action="{{ route('logout') }}" method="POST" class="ml-6 border-l border-gray-100 pl-4 flex items-center">
                    @csrf
                    <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors" title="Logout">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
            
            <!-- Category Pills -->
            <div class="bg-white rounded-full p-1.5 flex shadow-sm">
                <button @click="activeCategory = 'all'" :class="{'bg-black text-white': activeCategory === 'all', 'text-gray-500 hover:text-gray-900': activeCategory !== 'all'}" class="px-6 py-2 rounded-full text-sm font-bold transition-colors">
                    Semua
                </button>
                @foreach($categories as $category)
                <button @click="activeCategory = '{{ $category->id }}'" :class="{'bg-black text-white': activeCategory === '{{ $category->id }}', 'text-gray-500 hover:text-gray-900': activeCategory !== '{{ $category->id }}'}" class="px-6 py-2 rounded-full text-sm font-bold transition-colors">
                    {{ $category->name }}
                </button>
                @endforeach
            </div>
        </div>

        <!-- Menu Grid -->
        <main class="flex-1 overflow-y-auto no-scrollbar pb-12 pt-8">
            <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-6 gap-y-16">
                
                <template x-for="menu in filteredMenus" :key="menu.id">
                    <!-- Card -->
                    <div class="bg-white rounded-[32px] p-6 pt-0 shadow-sm relative flex flex-col items-center text-center mt-10 transition-transform hover:-translate-y-1">
                        <!-- Image overlapping top -->
                        <div class="w-32 h-32 rounded-full border-[6px] border-white shadow-sm absolute -top-16 overflow-hidden bg-gray-50 flex items-center justify-center">
                            <template x-if="menu.image">
                                <img :src="menu.image.startsWith('http') ? menu.image : '/storage/' + menu.image" :alt="menu.name" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!menu.image">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8 text-gray-300"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                            </template>
                        </div>
                        
                        <div class="mt-20 w-full flex-1 flex flex-col">
                            <h3 class="font-extrabold text-gray-900 text-lg mb-1.5" x-text="menu.name"></h3>
                            <p class="text-xs text-gray-400 font-medium leading-relaxed mb-6 flex-1 line-clamp-3" x-text="menu.description"></p>
                            
                            <div class="flex items-center justify-between w-full mt-auto">
                                <span class="font-extrabold text-gray-900 text-lg" x-text="'Rp ' + (menu.price/1000) + '.000'"></span>
                                
                                <template x-if="getItemQuantity(menu.id) === 0">
                                    <button @click="addToCart(menu)" class="bg-[#ffb000] hover:bg-[#e59e00] text-black font-extrabold px-5 py-2.5 rounded-xl text-sm flex items-center shadow-sm transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-2"><path d="M5 12h14"/><path d="M12 5v14"/></svg> Tambah
                                    </button>
                                </template>

                                <template x-if="getItemQuantity(menu.id) > 0">
                                    <div class="bg-gray-100 rounded-xl flex items-center p-1">
                                        <button @click="updateQuantity(menu.id, -1)" class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-gray-600 shadow-sm hover:text-red-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="M5 12h14"/></svg>
                                        </button>
                                        <span class="font-extrabold text-gray-900 w-8 text-center text-sm" x-text="getItemQuantity(menu.id)"></span>
                                        <button @click="updateQuantity(menu.id, 1)" class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-gray-600 shadow-sm hover:text-green-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Empty State -->
                <div x-show="filteredMenus.length === 0" class="col-span-full py-20 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white shadow-sm mb-4">
                        <i data-lucide="search-X" class="w-8 h-8 text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Tidak ada menu</h3>
                    <p class="text-gray-500 mt-1 font-medium text-sm">Coba pilih kategori lain.</p>
                </div>

            </div>
        </main>
    </div>

    <!-- Right Cart Panel -->
    <aside class="w-[400px] my-6 mr-6 rounded-[32px] flex flex-col relative shadow-2xl shrink-0" style="background-color: #a41c1c;">
        <!-- Top half -->
        <div class="p-8 pb-4 flex-1 flex flex-col overflow-hidden">
            <div class="flex justify-between items-center mb-6 text-white">
                <h2 class="text-2xl font-extrabold">Your order</h2>
                <span class="text-xs font-bold opacity-70">ID: #{{ rand(1000, 9999) }}</span>
            </div>
            
            <!-- Dine in / Takeaway -->
            <div class="bg-[#8b1616] p-1.5 rounded-2xl flex mb-6 shadow-inner">
                <button @click="orderType = 'dine_in'" :class="{'bg-white text-[#a41c1c] shadow-sm': orderType === 'dine_in', 'text-white/70 hover:text-white': orderType !== 'dine_in'}" class="flex-1 font-extrabold py-2.5 rounded-xl text-sm transition-all">Dine In</button>
                <button @click="orderType = 'takeaway'" :class="{'bg-white text-[#a41c1c] shadow-sm': orderType === 'takeaway', 'text-white/70 hover:text-white': orderType !== 'takeaway'}" class="flex-1 font-extrabold py-2.5 rounded-xl text-sm transition-all">Takeaway</button>
            </div>
            
            <!-- Customer Name -->
            <div class="mb-6 relative">
                <i data-lucide="user" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-white opacity-50"></i>
                <input type="text" x-model="customerName" placeholder="Customer Name" class="w-full bg-[#8b1616] border border-[#bd2c2c] text-white placeholder-white/40 rounded-2xl py-3.5 pl-11 pr-4 text-sm font-bold focus:outline-none focus:border-white/50 transition-colors shadow-inner">
            </div>

            <!-- Items -->
            <div class="flex-1 overflow-y-auto space-y-5 pr-2 no-scrollbar">
                
                <template x-for="item in cart" :key="item.id">
                    <div class="flex items-center">
                        <div class="w-14 h-14 rounded-2xl overflow-hidden mr-4 border-2 border-white/10 bg-[#8b1616] flex-shrink-0 flex items-center justify-center">
                            <template x-if="item.image">
                                <img :src="item.image.startsWith('http') ? item.image : '/storage/' + item.image" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!item.image">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-white/30"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                            </template>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start">
                                <h4 class="text-white font-extrabold text-sm truncate pr-2" x-text="item.name"></h4>
                                <button class="text-white/40 hover:text-white mt-0.5"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg></button>
                            </div>
                            <div class="text-white/80 font-bold text-xs mt-1" x-text="'Rp ' + (item.price/1000) + '.000'"></div>
                        </div>
                        <div class="flex items-center space-x-3 ml-4">
                            <button @click="updateQuantity(item.id, -1)" class="w-7 h-7 rounded-full border border-white/20 flex items-center justify-center text-white hover:bg-white/10 transition-colors"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="M5 12h14"/></svg></button>
                            <span class="text-white font-extrabold text-sm w-4 text-center" x-text="item.quantity"></span>
                            <button @click="updateQuantity(item.id, 1)" class="w-7 h-7 rounded-full border border-white/20 flex items-center justify-center text-white hover:bg-white/10 transition-colors"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="M5 12h14"/><path d="M12 5v14"/></svg></button>
                        </div>
                    </div>
                </template>

                <!-- Empty Cart State -->
                <div x-show="cart.length === 0" class="h-full flex flex-col items-center justify-center text-white/30 pt-10">
                    <i data-lucide="shopping-cart" class="w-12 h-12 mb-4 opacity-50"></i>
                    <p class="text-sm font-bold">Keranjang kosong</p>
                </div>

            </div>
        </div>

        <!-- Cutout Divider -->
        <div class="relative h-px w-full my-1 shrink-0">
            <div class="absolute left-0 -translate-x-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-[#eceeef]"></div>
            <div class="w-full border-t-2 border-dashed border-white/20 absolute top-0"></div>
            <div class="absolute right-0 translate-x-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-[#eceeef]"></div>
        </div>

        <!-- Bottom half -->
        <div class="p-8 pt-6 shrink-0">
            <!-- Summary -->
            <div class="space-y-3 mb-6">
                <div class="flex justify-between text-sm font-bold text-white/70"><span>Subtotal</span><span x-text="formatMoney(subtotal)"></span></div>
                <div class="flex justify-between text-sm font-bold text-white/70"><span>Tax (10%)</span><span x-text="formatMoney(tax)"></span></div>
            </div>
            
            <div class="flex justify-between items-end mb-8 text-white">
                <span class="text-xl font-extrabold">Total</span>
                <span class="text-3xl font-black tracking-tight" x-text="formatMoney(total)"></span>
            </div>

            <!-- Payment Methods -->
            <div class="flex space-x-3 mb-6">
                <button @click="paymentMethod = 'cash'" :class="{'bg-white text-[#a41c1c] shadow-md': paymentMethod === 'cash', 'bg-[#8b1616] border border-[#bd2c2c] text-white': paymentMethod !== 'cash'}" class="flex-1 font-extrabold py-3.5 rounded-2xl text-sm transition-all">Cash</button>
                <button @click="paymentMethod = 'qris'" :class="{'bg-white text-[#a41c1c] shadow-md': paymentMethod === 'qris', 'bg-[#8b1616] border border-[#bd2c2c] text-white': paymentMethod !== 'qris'}" class="flex-1 font-extrabold py-3.5 rounded-2xl text-sm transition-all">QRIS</button>
                <button @click="paymentMethod = 'debit'" :class="{'bg-white text-[#a41c1c] shadow-md': paymentMethod === 'debit', 'bg-[#8b1616] border border-[#bd2c2c] text-white': paymentMethod !== 'debit'}" class="flex-1 font-extrabold py-3.5 rounded-2xl text-sm transition-all">Debit</button>
            </div>

            <!-- Upload Payment Proof (only for non-cash) -->
            <div x-show="paymentMethod !== 'cash'" class="mb-6">
                <label class="block text-xs font-bold text-white/70 mb-2">Unggah Bukti Pembayaran</label>
                <input type="file" @change="paymentProof = $event.target.files[0]" id="payment_proof_input" accept="image/*,.pdf"
                       class="w-full text-sm text-white/70 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-white file:text-[#a41c1c] hover:file:bg-gray-100 transition-all cursor-pointer bg-[#8b1616] border border-[#bd2c2c] rounded-2xl shadow-inner p-1">
            </div>

            <!-- Confirm Button -->
            <button @click="processPayment()" :disabled="cart.length === 0" 
                    :class="{'opacity-50 cursor-not-allowed': cart.length === 0, 'hover:bg-[#e59e00] hover:scale-[1.02]': cart.length > 0}"
                    class="w-full bg-[#ffb000] text-black font-black py-4.5 rounded-2xl text-base shadow-xl transition-all duration-200 uppercase tracking-wide flex items-center justify-center">
                Confirm order
            </button>
        </div>
    </aside>

</div>

<!-- Alpine.js Application Logic -->
<script>
    const menusData = @json($menus);
    
    function posApp() {
        return {
            menus: menusData,
            activeCategory: 'all',
            searchQuery: '',
            cart: [],
            orderType: 'dine_in',
            paymentMethod: 'cash',
            customerName: '',
            paymentProof: null,

            get filteredMenus() {
                return this.menus.filter(menu => {
                    const matchCategory = this.activeCategory === 'all' || menu.category_id == this.activeCategory;
                    const matchSearch = menu.name.toLowerCase().includes(this.searchQuery.toLowerCase());
                    return matchCategory && matchSearch;
                });
            },

            getItemQuantity(menuId) {
                const item = this.cart.find(i => i.id === menuId);
                return item ? item.quantity : 0;
            },

            addToCart(menu) {
                const existing = this.cart.find(item => item.id === menu.id);
                if (existing) {
                    existing.quantity++;
                } else {
                    this.cart.push({
                        id: menu.id,
                        name: menu.name,
                        price: menu.price,
                        image: menu.image,
                        quantity: 1
                    });
                }
            },

            updateQuantity(id, delta) {
                const item = this.cart.find(i => i.id === id);
                if (item) {
                    item.quantity += delta;
                    if (item.quantity <= 0) {
                        this.cart = this.cart.filter(i => i.id !== id);
                    }
                }
            },

            get subtotal() {
                return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            },

            get tax() {
                return this.subtotal * 0.10; // 10% tax
            },

            get total() {
                return this.subtotal + this.tax;
            },

            formatMoney(amount) {
                return 'Rp ' + amount.toLocaleString('id-ID');
            },

            async processPayment() {
                if(!this.customerName) {
                    alert('Silakan masukkan nama pelanggan!');
                    return;
                }

                if(this.cart.length === 0) {
                    alert('Keranjang masih kosong!');
                    return;
                }

                if(this.paymentMethod !== 'cash' && !this.paymentProof) {
                    alert('Silakan unggah foto bukti pembayaran terlebih dahulu!');
                    return;
                }

                const formData = new FormData();
                formData.append('customer_name', this.customerName);
                formData.append('order_type', this.orderType);
                formData.append('payment_method', this.paymentMethod);
                formData.append('cart', JSON.stringify(this.cart));
                if (this.paymentProof) {
                    formData.append('payment_proof', this.paymentProof);
                }

                try {
                    const response = await fetch('{{ route("pos.checkout") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (data.success) {
                        alert('Berhasil! Pesanan untuk ' + this.customerName + ' telah disimpan ke database.');
                        // Reset order
                        this.cart = [];
                        this.customerName = '';
                        this.paymentMethod = 'cash';
                        this.paymentProof = null;
                        const fileInput = document.getElementById('payment_proof_input');
                        if(fileInput) fileInput.value = '';
                    } else {
                        alert('Gagal memproses pesanan: ' + data.message);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan sistem saat memproses pesanan.');
                }
            }
        }
    }
</script>

<style>
    /* Hide scrollbar for Chrome, Safari and Opera */
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    /* Hide scrollbar for IE, Edge and Firefox */
    .no-scrollbar {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
</style>
@endsection
