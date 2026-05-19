@extends('layouts.app')

@section('title', 'Detail Pesanan #' . substr($order->order_number, -4))

@section('header')
<header class="flex items-start justify-between bg-transparent pb-2 max-w-[1000px] mx-auto w-full">
    <!-- Left side -->
    <div class="mt-2">
        <div class="flex items-center text-[11px] font-semibold text-gray-400 tracking-wide uppercase mb-1">
            <a href="{{ route('orders') }}" class="text-gray-400 hover:text-[#ea580c] transition-colors flex items-center">
                <i data-lucide="arrow-left" class="w-3 h-3 mr-1"></i>
                Riwayat Pesanan
            </a>
            <i data-lucide="chevron-right" class="w-3 h-3 mx-1"></i>
            <span class="text-[#ea580c]">Detail Pesanan</span>
        </div>
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight flex items-center">
            Pesanan #{{ substr($order->order_number, -4) }}
        </h2>
    </div>

    <!-- Right side -->
    <div class="flex items-center space-x-3 mt-4">
        <a href="{{ route('orders.receipt') }}?order_id={{ $order->order_number }}" target="_blank" class="bg-[#ea580c] text-white px-6 py-3 rounded-2xl font-extrabold text-sm flex items-center hover:bg-[#c2410c] transition-colors shadow-lg shadow-orange-500/30 transform hover:-translate-y-0.5 active:translate-y-0">
            <i data-lucide="printer" class="w-5 h-5 mr-2"></i>
            Cetak Bukti Pembayaran
        </a>
    </div>
</header>
@endsection

@section('content')
<div class="max-w-[1000px] mx-auto pb-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Left Column: Details & Items -->
        <div class="md:col-span-2 space-y-6">
            
            <!-- Items Card -->
            <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-extrabold text-gray-900 flex items-center">
                        <i data-lucide="shopping-bag" class="w-5 h-5 mr-2 text-gray-400"></i>
                        Daftar Menu
                    </h3>
                </div>
                <div class="p-0">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Menu</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Qty</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Harga</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @foreach($order->items as $item)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($item->menu->image)
                                            @if(Str::startsWith($item->menu->image, 'http'))
                                                <img src="{{ $item->menu->image }}" alt="{{ $item->menu->name }}" class="w-10 h-10 rounded-xl object-cover mr-3 border border-gray-100">
                                            @else
                                                <img src="{{ Storage::url($item->menu->image) }}" alt="{{ $item->menu->name }}" class="w-10 h-10 rounded-xl object-cover mr-3 border border-gray-100">
                                            @endif
                                        @else
                                            <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center mr-3 border border-gray-200">
                                                <i data-lucide="image" class="w-5 h-5 text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div class="text-sm font-bold text-gray-900">{{ $item->menu->name }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-lg text-sm font-extrabold bg-gray-100 text-gray-800">
                                        {{ $item->quantity }}x
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-gray-500">
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-extrabold text-gray-900">
                                    Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-5 border-t border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <span class="text-gray-500 font-bold">Total Pembayaran</span>
                    <span class="text-2xl font-black text-gray-900">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
            
        </div>

        <!-- Right Column: Info -->
        <div class="space-y-6">
            
            <!-- Customer Card -->
            <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 overflow-hidden p-6 relative">
                <div class="absolute top-0 right-0 p-4">
                    <i data-lucide="user" class="w-12 h-12 text-gray-50"></i>
                </div>
                <h3 class="text-xs font-extrabold text-gray-400 uppercase tracking-widest mb-4">Informasi Pelanggan</h3>
                <div class="flex items-center mb-4">
                    <div class="h-12 w-12 rounded-full overflow-hidden bg-gray-100 flex-shrink-0 border-2 border-white shadow-sm">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($order->customer_name) }}&background=random" alt="" class="h-full w-full object-cover">
                    </div>
                    <div class="ml-3">
                        <p class="text-lg font-black text-gray-900 leading-tight">{{ $order->customer_name }}</p>
                        <p class="text-sm font-medium text-gray-500">Pelanggan</p>
                    </div>
                </div>
            </div>

            <!-- Order Info Card -->
            <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 overflow-hidden p-6">
                <h3 class="text-xs font-extrabold text-gray-400 uppercase tracking-widest mb-4">Informasi Pesanan</h3>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 uppercase">Tanggal & Waktu</p>
                        <p class="text-sm font-bold text-gray-900">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 uppercase">Tipe Pesanan</p>
                        <p class="text-sm font-bold text-gray-900 flex items-center">
                            <i data-lucide="{{ $order->notes == 'takeaway' ? 'shopping-bag' : 'utensils' }}" class="w-4 h-4 mr-2 text-gray-400"></i>
                            {{ ucfirst($order->notes) }}
                        </p>
                    </div>

                    <div>
                        <p class="text-[11px] font-bold text-gray-400 uppercase">Metode Pembayaran</p>
                        <p class="text-sm font-bold text-gray-900 flex items-center">
                            <i data-lucide="{{ $order->payment_method == 'cash' ? 'banknote' : ($order->payment_method == 'qris' ? 'qr-code' : 'credit-card') }}" class="w-4 h-4 mr-2 {{ $order->payment_method == 'cash' ? 'text-green-500' : ($order->payment_method == 'qris' ? 'text-purple-500' : 'text-blue-500') }}"></i>
                            {{ ucfirst($order->payment_method) }}
                        </p>
                    </div>

                    <div>
                        <p class="text-[11px] font-bold text-gray-400 uppercase">Kasir</p>
                        <p class="text-sm font-bold text-gray-900 flex items-center">
                            <i data-lucide="shield-check" class="w-4 h-4 mr-2 text-gray-400"></i>
                            {{ $order->user->name ?? 'Admin' }}
                        </p>
                    </div>
                    
                    @if($order->payment && $order->payment->payment_proof)
                    <div class="pt-4 border-t border-gray-100">
                        <p class="text-[11px] font-bold text-gray-400 uppercase mb-3">Bukti Pembayaran</p>
                        <a href="{{ Storage::url($order->payment->payment_proof) }}" target="_blank" class="block rounded-xl overflow-hidden border border-gray-200 hover:border-[#ea580c] transition-colors group relative">
                            @if(in_array(strtolower(pathinfo($order->payment->payment_proof, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                <img src="{{ Storage::url($order->payment->payment_proof) }}" alt="Bukti Pembayaran" class="w-full object-cover max-h-48">
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="text-white font-bold text-sm flex items-center"><i data-lucide="external-link" class="w-4 h-4 mr-1"></i> Buka Gambar</span>
                                </div>
                            @else
                                <div class="py-4 px-4 flex items-center justify-center bg-gray-50 text-[#ea580c] font-bold text-sm">
                                    <i data-lucide="file-text" class="w-5 h-5 mr-2"></i> Buka Dokumen (PDF)
                                </div>
                            @endif
                        </a>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
