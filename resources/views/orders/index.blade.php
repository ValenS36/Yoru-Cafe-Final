@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@section('header')
<header class="flex items-start justify-between bg-transparent pb-2 max-w-[1400px] mx-auto w-full">
    <!-- Left side -->
    <div class="mt-2">
        <div class="flex items-center text-[11px] font-semibold text-gray-400 tracking-wide uppercase mb-1">
            <span class="text-[#ea580c]">YORUCAFE POS</span>
            <i data-lucide="chevron-right" class="w-3 h-3 mx-1"></i>
            <span>Riwayat Pesanan</span>
        </div>
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
            Riwayat Pesanan
        </h2>
    </div>

    <!-- Right side - Stat Cards -->
    <div class="flex items-center space-x-3">
        <!-- Pending Card -->
        <div class="flex items-center bg-white px-5 py-3 rounded-[20px] shadow-sm border border-gray-100">
            <div class="w-10 h-10 rounded-full bg-yellow-50 flex items-center justify-center text-yellow-500 mr-3">
                <i data-lucide="clock" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Pending</p>
                <p class="text-xl font-extrabold text-gray-900 leading-none mt-0.5">{{ $stats['pending'] }}</p>
            </div>
        </div>

        <!-- Selesai Card -->
        <div class="flex items-center bg-white px-5 py-3 rounded-[20px] shadow-sm border border-gray-100">
            <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-green-500 mr-3">
                <i data-lucide="check-circle-2" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Selesai</p>
                <p class="text-xl font-extrabold text-gray-900 leading-none mt-0.5">{{ $stats['completed'] }}</p>
            </div>
        </div>

    </div>
</header>
@endsection

@section('content')
<div class="max-w-[1400px] mx-auto pb-8">
    
    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center shadow-sm">
        <i data-lucide="check-circle" class="w-5 h-5 mr-3"></i>
        <span class="text-sm font-bold">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center shadow-sm">
        <i data-lucide="alert-circle" class="w-5 h-5 mr-3"></i>
        <span class="text-sm font-bold">{{ session('error') }}</span>
    </div>
    @endif

    <!-- Toolbar -->
    <form action="{{ route('orders') }}" method="GET" class="bg-white rounded-[20px] p-2 flex items-center justify-between shadow-sm border border-gray-100 mb-6">
        <!-- Search -->
        <div class="relative w-1/3 flex-1 max-w-md ml-2">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i data-lucide="search" class="h-4 w-4 text-gray-400"></i>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama atau ID..." class="block w-full pl-10 pr-3 py-2.5 border-transparent bg-gray-50 rounded-xl leading-5 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary focus:border-transparent sm:text-sm font-medium transition-colors">
        </div>

        <!-- Filters & Actions -->
        <div class="flex items-center space-x-2 mr-2">
            <!-- Date Filter -->
            <div class="relative">
                <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()" class="bg-gray-50 border-transparent rounded-xl py-2 px-4 text-sm font-bold text-gray-600 focus:outline-none focus:ring-2 focus:ring-primary cursor-pointer transition-colors hover:bg-gray-100">
            </div>

            <!-- Status Filter -->
            <div class="relative">
                <select name="status" onchange="this.form.submit()" class="appearance-none bg-gray-50 border-transparent rounded-xl py-2.5 pl-4 pr-10 text-sm font-bold text-gray-600 focus:outline-none focus:ring-2 focus:ring-primary cursor-pointer transition-colors hover:bg-gray-100">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                    <i data-lucide="chevron-down" class="h-4 w-4"></i>
                </div>
            </div>

        </div>
    </form>

    <!-- Table Container -->
    <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead>
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-[10px] font-extrabold text-gray-400 uppercase tracking-widest bg-white">Order ID</th>
                        <th scope="col" class="px-6 py-4 text-left text-[10px] font-extrabold text-gray-400 uppercase tracking-widest bg-white">Nama Pelanggan</th>
                        <th scope="col" class="px-6 py-4 text-left text-[10px] font-extrabold text-gray-400 uppercase tracking-widest bg-white">Tipe</th>
                        <th scope="col" class="px-6 py-4 text-left text-[10px] font-extrabold text-gray-400 uppercase tracking-widest bg-white">Pembayaran</th>
                        <th scope="col" class="px-6 py-4 text-left text-[10px] font-extrabold text-gray-400 uppercase tracking-widest bg-white">Total Harga</th>
                        <th scope="col" class="px-6 py-4 text-left text-[10px] font-extrabold text-gray-400 uppercase tracking-widest bg-white">Status</th>
                        <th scope="col" class="px-6 py-4 text-left text-[10px] font-extrabold text-gray-400 uppercase tracking-widest bg-white">Tanggal & Waktu</th>
                        <th scope="col" class="px-6 py-4 text-left text-[10px] font-extrabold text-gray-400 uppercase tracking-widest bg-white rounded-tr-[24px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-50">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-extrabold text-red-500">#{{ substr($order->order_number, -4) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full overflow-hidden bg-gray-100 flex-shrink-0">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($order->customer_name) }}&background=random" alt="" class="h-full w-full object-cover">
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-bold text-gray-900">{{ $order->customer_name }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold {{ $order->notes == 'takeaway' ? 'text-orange-600 bg-orange-50' : 'text-blue-600 bg-blue-50' }}">
                                <i data-lucide="{{ $order->notes == 'takeaway' ? 'shopping-bag' : 'utensils' }}" class="w-3 h-3 mr-1.5"></i> {{ ucfirst($order->notes) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center text-sm font-bold text-gray-600">
                                <i data-lucide="{{ $order->payment_method == 'cash' ? 'banknote' : ($order->payment_method == 'qris' ? 'qr-code' : 'credit-card') }}" class="w-4 h-4 {{ $order->payment_method == 'cash' ? 'text-green-500' : ($order->payment_method == 'qris' ? 'text-purple-500' : 'text-blue-500') }} mr-2"></i> {{ ucfirst($order->payment_method) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-extrabold text-gray-900">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form action="{{ route('orders.updateStatus', $order) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <div class="relative group">
                                    <select name="status" onchange="this.form.submit()" 
                                        class="appearance-none inline-flex items-center px-3 py-1.5 pr-8 rounded-full text-xs font-extrabold border-0 cursor-pointer focus:ring-2 focus:ring-offset-2 transition-all duration-200
                                        {{ $order->status == 'completed' 
                                            ? 'text-green-700 bg-green-100 focus:ring-green-500 hover:bg-green-200' 
                                            : 'text-orange-700 bg-orange-100 focus:ring-orange-500 hover:bg-orange-200' }}">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 {{ $order->status == 'completed' ? 'text-green-600' : 'text-orange-600' }}">
                                        <i data-lucide="chevron-down" class="h-3 w-3"></i>
                                    </div>
                                </div>
                            </form>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 font-medium">
                            <div class="text-gray-900 font-bold">{{ $order->created_at->format('d M Y') }}</div>
                            <div>{{ $order->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-colors flex items-center inline-flex font-bold">
                                <i data-lucide="eye" class="w-4 h-4 mr-1.5"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-10 text-center text-gray-400 font-medium">Belum ada data pesanan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-white px-6 py-4 border-t border-gray-100 flex items-center justify-between dynamic-pagination">
            <div class="text-xs font-medium text-gray-500">
                Menampilkan <span class="font-bold text-gray-900">{{ $orders->firstItem() ?? 0 }}-{{ $orders->lastItem() ?? 0 }}</span> dari <span class="font-bold text-gray-900">{{ $orders->total() }}</span> pesanan
            </div>
            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
