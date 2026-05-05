<header class="flex items-start justify-between bg-transparent pb-2">
    <!-- Left side -->
    <div>
        <div class="flex items-center text-[11px] font-semibold text-gray-400 tracking-wide uppercase mb-1">
            <span class="text-[#ea580c]">YORUCAFE</span>
            <i data-lucide="chevron-right" class="w-3 h-3 mx-1"></i>
            <span>Dashboard</span>
        </div>
        <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight flex items-center">
            Selamat Datang, {{ explode(' ', Auth::user()->name ?? 'Admin')[0] }}! <span class="ml-2 text-xl">👋</span>
        </h2>
        <p class="text-xs font-medium text-gray-500 mt-0.5">{{ now()->translatedFormat('l, d F Y') }} — Rangkuman aktivitas hari ini</p>
    </div>

    <!-- Right side -->
    <div class="flex items-center space-x-3">
        <!-- Date Badge -->
        <div class="hidden md:flex items-center bg-white px-4 py-2 rounded-full shadow-sm border border-gray-100 space-x-2">
            <i data-lucide="calendar" class="w-4 h-4 text-[#ea580c]"></i>
            <span class="text-sm font-semibold text-gray-700">{{ now()->format('d M Y') }}</span>
        </div>

        <!-- Notifications -->
        @php
            $recentOrders = \App\Models\Order::latest()->take(5)->get();
            $hasNotifications = $recentOrders->count() > 0;
        @endphp
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="relative bg-white p-2.5 rounded-full text-gray-400 hover:text-gray-600 shadow-sm border border-gray-100 transition-all focus:outline-none">
                @if($hasNotifications)
                    <span class="absolute top-2.5 right-2.5 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                @endif
                <i data-lucide="bell" class="w-5 h-5"></i>
            </button>

            <!-- Dropdown -->
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 @click.away="open = false"
                 class="absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden"
                 style="display: none;">
                <div class="px-4 py-3 border-bottom border-gray-50 flex items-center justify-between bg-gray-50/50">
                    <h3 class="text-sm font-bold text-gray-900">Pesanan Terbaru</h3>
                    <span class="text-[10px] font-bold text-[#ea580c] bg-[#fff0eb] px-2 py-0.5 rounded-full">New</span>
                </div>
                <div class="divide-y divide-gray-50 max-h-[400px] overflow-y-auto">
                    @forelse($recentOrders as $order)
                        <a href="{{ route('orders') }}?search={{ $order->order_number }}" class="block px-4 py-3 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start">
                                <div class="w-8 h-8 rounded-full bg-orange-50 flex items-center justify-center text-[#ea580c] mr-3 shrink-0">
                                    <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-gray-900 truncate">
                                        Pesanan #{{ substr($order->order_number, -4) }}
                                    </p>
                                    <p class="text-[11px] text-gray-500 mt-0.5">
                                        {{ $order->customer_name }} — Rp {{ number_format($order->total/1000, 0) }}rb
                                    </p>
                                    <p class="text-[10px] text-gray-400 mt-1 flex items-center">
                                        <i data-lucide="clock" class="w-3 h-3 mr-1"></i>
                                        {{ $order->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="px-4 py-8 text-center">
                            <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i data-lucide="bell-off" class="w-6 h-6 text-gray-300"></i>
                            </div>
                            <p class="text-xs font-medium text-gray-500">Belum ada pesanan masuk</p>
                        </div>
                    @endforelse
                </div>
                <a href="{{ route('orders') }}" class="block py-2.5 text-center text-[11px] font-bold text-gray-500 hover:text-[#ea580c] bg-gray-50/50 transition-colors">
                    Lihat Semua Riwayat
                </a>
            </div>
        </div>

        <!-- User Profile Pic -->
        <a href="{{ route('profile') }}" class="h-10 w-10 rounded-full border-2 border-white shadow-sm overflow-hidden bg-gray-200 cursor-pointer block hover:ring-2 hover:ring-[#ea580c] transition-all">
            <img src="{{ Auth::user()->image ? Storage::url(Auth::user()->image) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name ?? 'Admin').'&background=ea580c&color=fff' }}" alt="User" class="w-full h-full object-cover">
        </a>
    </div>
</header>
