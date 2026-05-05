<!-- Sidebar Component -->
<aside class="w-[260px] bg-white rounded-3xl shadow-sm flex flex-col transition-all duration-300 h-full relative z-20">
    <!-- Logo -->
    <div class="p-6 flex items-center space-x-3">
        <div class="w-10 h-10 bg-[#ea580c] rounded-xl overflow-hidden flex-shrink-0 flex items-center justify-center p-1.5">
            <img src="{{ asset('images/logo.png') }}" alt="YoruCafe Logo" class="w-full h-full object-contain">
        </div>
        <div>
            <h1 class="font-bold text-gray-900 leading-tight">YoruCafe</h1>
            <p class="text-xs text-gray-400 font-medium">Admin Panel</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto px-4 py-2 custom-scrollbar">
        <!-- MENU UTAMA -->
        <div class="text-[10px] font-bold text-gray-400 tracking-wider uppercase mb-3 ml-2">Menu Utama</div>
        <ul class="space-y-1.5 mb-8">
            <li>
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-2xl {{ request()->routeIs('dashboard') ? 'bg-[#fff0eb] text-[#ea580c] font-bold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900 font-medium' }} transition-all group">
                    <div class="{{ request()->routeIs('dashboard') ? 'bg-[#ea580c] text-white' : 'bg-gray-100 text-gray-500' }} w-8 h-8 rounded-lg flex items-center justify-center mr-3">
                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                    </div>
                    <span class="text-sm">Dashboard</span>
                </a>
            </li>
            
            <li>
                <a href="{{ route('menu') }}" class="flex items-center px-4 py-3 rounded-2xl {{ request()->routeIs('menu') ? 'bg-[#fff0eb] text-[#ea580c] font-bold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900 font-medium' }} transition-all group">
                    <div class="{{ request()->routeIs('menu') ? 'bg-[#ea580c] text-white' : 'bg-gray-100 text-gray-500' }} w-8 h-8 rounded-lg flex items-center justify-center mr-3">
                        <i data-lucide="utensils" class="w-4 h-4"></i>
                    </div>
                    <span class="text-sm">Manajemen Menu</span>
                </a>
            </li>
            
            <li>
                <a href="{{ route('orders') }}" class="flex items-center px-4 py-3 rounded-2xl {{ request()->routeIs('orders') ? 'bg-[#fff0eb] text-[#ea580c] font-bold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900 font-medium' }} transition-all group justify-between">
                    <div class="flex items-center">
                        <div class="{{ request()->routeIs('orders') ? 'bg-[#ea580c] text-white' : 'bg-gray-100 text-gray-500' }} w-8 h-8 rounded-lg flex items-center justify-center mr-3">
                            <i data-lucide="history" class="w-4 h-4"></i>
                        </div>
                        <span class="text-sm">Riwayat Pesanan</span>
                    </div>
                </a>
            </li>
            
            <li>
                <a href="{{ route('reports') }}" class="flex items-center px-4 py-3 rounded-2xl {{ request()->routeIs('reports') ? 'bg-[#fff0eb] text-[#ea580c] font-bold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900 font-medium' }} transition-all group">
                    <div class="{{ request()->routeIs('reports') ? 'bg-[#ea580c] text-white' : 'bg-gray-100 text-gray-500' }} w-8 h-8 rounded-lg flex items-center justify-center mr-3">
                        <i data-lucide="bar-chart-2" class="w-4 h-4"></i>
                    </div>
                    <span class="text-sm">Laporan</span>
                </a>
            </li>
        </ul>

        <!-- PENGATURAN -->
        <div class="text-[10px] font-bold text-gray-400 tracking-wider uppercase mb-3 ml-2">Pengaturan</div>
        <ul class="space-y-1.5">
            <li>
                <a href="{{ route('profile') }}" class="flex items-center px-4 py-3 rounded-2xl {{ request()->routeIs('profile') ? 'bg-[#fff0eb] text-[#ea580c] font-bold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900 font-medium' }} transition-all group">
                    <div class="{{ request()->routeIs('profile') ? 'bg-[#ea580c] text-white' : 'bg-gray-100 text-gray-500' }} w-8 h-8 rounded-lg flex items-center justify-center mr-3">
                        <i data-lucide="settings" class="w-4 h-4"></i>
                    </div>
                    <span class="text-sm">Pengaturan</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Bottom User Profile -->
    <div class="p-4 mt-auto">
        <div class="bg-gray-50 rounded-2xl p-3 flex items-center justify-between border border-gray-100">
            <div class="flex items-center space-x-3">
                <div class="relative">
                    <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden">
                        <img src="{{ Auth::user()->image ? Storage::url(Auth::user()->image) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=ea580c&color=fff' }}" alt="User">
                    </div>
                    <div class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-white rounded-full"></div>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900">{{ Auth::user()->name ?? 'Admin Yoru' }}</p>
                    <p class="text-[10px] text-gray-500 font-medium flex items-center">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1"></span> Online
                    </p>
                </div>
            </div>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors p-1.5 hover:bg-white rounded-lg">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </div>
</aside>

<style>
/* Custom Scrollbar for Sidebar */
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #e5e7eb;
    border-radius: 10px;
}
.custom-scrollbar:hover::-webkit-scrollbar-thumb {
    background: #d1d5db;
}
</style>
