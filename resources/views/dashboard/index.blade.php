@extends('layouts.app')

@section('title', 'Dashboard Overview')

@section('header')
<header class="flex items-start justify-between bg-transparent pb-2 max-w-[1400px] mx-auto w-full">
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
        <button class="relative bg-white p-2.5 rounded-full text-gray-400 hover:text-gray-600 shadow-sm border border-gray-100 transition-all focus:outline-none">
            <span class="absolute top-2.5 right-2.5 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
            <i data-lucide="bell" class="w-5 h-5"></i>
        </button>

        <!-- User Profile Pic -->
        <a href="{{ route('profile') }}" class="h-10 w-10 rounded-full border-2 border-white shadow-sm overflow-hidden bg-gray-200 cursor-pointer block hover:ring-2 hover:ring-[#ea580c] transition-all">
            <img src="{{ Auth::user()->image ? Storage::url(Auth::user()->image) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=ea580c&color=fff' }}" alt="User" class="w-full h-full object-cover">
        </a>
    </div>
</header>
@endsection

@section('content')
<div class="space-y-4 max-w-[1400px] mx-auto pb-8">
    
    <!-- Top Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        
        <!-- Total Pendapatan Card -->
        <div class="bg-gradient-to-br from-[#f15c32] to-[#ea580c] rounded-[24px] p-6 text-white shadow-md relative overflow-hidden group">
            <!-- Decorative Circle -->
            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/10 rounded-full blur-xl group-hover:bg-white/20 transition-all"></div>
            
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center backdrop-blur-sm">
                    <i data-lucide="wallet" class="w-5 h-5 text-white"></i>
                </div>
                <div class="flex items-center space-x-1 bg-white/20 px-2.5 py-1 rounded-full backdrop-blur-sm">
                    <i data-lucide="trending-up" class="w-3 h-3 text-white"></i>
                    <span class="text-xs font-bold text-white">+12.4%</span>
                </div>
            </div>
            
            <div class="relative z-10">
                <h3 class="text-xs font-bold text-white/80 uppercase tracking-wider mb-1">Total Pendapatan</h3>
                <div class="text-3xl font-extrabold mb-1 tracking-tight">Rp {{ number_format($totalRevenue / 1000, 1, ',', '.') }}rb</div>
                <p class="text-[11px] text-white/70 font-medium">Transaksi lunas hari ini</p>
            </div>
        </div>

        <!-- Total Pesanan Card -->
        <div class="bg-gradient-to-br from-[#f36b46] to-[#f15c32] rounded-[24px] p-6 text-white shadow-md relative overflow-hidden group">
            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/10 rounded-full blur-xl group-hover:bg-white/20 transition-all"></div>
            
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center backdrop-blur-sm">
                    <i data-lucide="receipt" class="w-5 h-5 text-white"></i>
                </div>
                <div class="flex items-center space-x-1 bg-white/20 px-2.5 py-1 rounded-full backdrop-blur-sm">
                    <i data-lucide="trending-up" class="w-3 h-3 text-white"></i>
                    <span class="text-xs font-bold text-white">+8.1%</span>
                </div>
            </div>
            
            <div class="relative z-10">
                <h3 class="text-xs font-bold text-white/80 uppercase tracking-wider mb-1">Total Pesanan Hari Ini</h3>
                <div class="text-3xl font-extrabold mb-1 tracking-tight">{{ $ordersToday }}</div>
                <p class="text-[11px] text-white/70 font-medium">Pesanan masuk hari ini</p>
            </div>
        </div>

        <!-- Pesanan Aktif Card -->
        <div class="bg-gradient-to-br from-[#f47a5a] to-[#f36b46] rounded-[24px] p-6 text-white shadow-md relative overflow-hidden group">
            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/10 rounded-full blur-xl group-hover:bg-white/20 transition-all"></div>
            
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center backdrop-blur-sm">
                    <i data-lucide="hourglass" class="w-5 h-5 text-white"></i>
                </div>
                <div class="flex items-center space-x-1 bg-[#ea580c]/50 px-2.5 py-1 rounded-full backdrop-blur-sm border border-white/20">
                    <div class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></div>
                    <span class="text-xs font-bold text-white">Live</span>
                </div>
            </div>
            
            <div class="relative z-10">
                <h3 class="text-xs font-bold text-white/80 uppercase tracking-wider mb-1">Pesanan Aktif (Pending)</h3>
                <div class="text-3xl font-extrabold mb-1 tracking-tight">{{ $pendingOrders }}</div>
                <p class="text-[11px] text-white/70 font-medium">Perlu diproses segera</p>
            </div>
        </div>

    </div>

    <!-- Chart Section -->
    <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 p-6">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h3 class="font-bold text-gray-900">Tren Penjualan</h3>
                <p class="text-xs text-gray-500 font-medium">7 hari terakhir · Pendapatan harian (Rp)</p>
            </div>
            <div class="flex space-x-3 text-[11px] font-bold">
                <div class="flex items-center space-x-1.5 bg-red-50 text-red-600 px-3 py-1.5 rounded-full">
                    <div class="w-2 h-2 rounded-full bg-red-500"></div>
                    <span>Pendapatan</span>
                </div>
                <div class="flex items-center space-x-1.5 bg-orange-50 text-orange-600 px-3 py-1.5 rounded-full">
                    <div class="w-2 h-2 rounded-full bg-orange-500"></div>
                    <span>Pesanan</span>
                </div>
            </div>
        </div>
        <div class="h-64 w-full relative">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <!-- Bottom Lists Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        
        <!-- Menu Terlaris -->
        <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="font-bold text-gray-900">Menu Terlaris</h3>
                    <p class="text-xs text-gray-500 font-medium">Top 5 item bulan ini</p>
                </div>
                <a href="{{ route('reports') }}" class="text-xs font-bold text-red-500 hover:text-red-700 flex items-center">
                    Lihat semua <i data-lucide="arrow-right" class="w-3 h-3 ml-1"></i>
                </a>
            </div>
            
            <div class="space-y-5">
                @forelse($bestSellers as $index => $item)
                <div class="flex items-center group cursor-pointer">
                    <div class="w-6 h-6 rounded-full {{ $index == 0 ? 'bg-[#f15c32] text-white' : ($index == 1 ? 'bg-[#fef08a] text-yellow-700' : 'bg-gray-100 text-gray-500') }} flex items-center justify-center text-xs font-bold mr-3 flex-shrink-0">{{ $index + 1 }}</div>
                    <div class="w-10 h-10 rounded-xl bg-gray-100 overflow-hidden flex-shrink-0 border border-gray-200">
                        @if($item->menu && $item->menu->image)
                            <img src="{{ Str::startsWith($item->menu->image, 'http') ? $item->menu->image : Storage::url($item->menu->image) }}" alt="{{ $item->menu->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-50"><i data-lucide="image" class="w-4 h-4 text-gray-300"></i></div>
                        @endif
                    </div>
                    <div class="ml-3 flex-1">
                        <div class="flex justify-between items-center mb-1.5">
                            <h4 class="text-sm font-bold text-gray-900 group-hover:text-[#ea580c] transition-colors">{{ $item->menu->name ?? 'Deleted Menu' }}</h4>
                            <span class="text-xs font-bold text-red-500">{{ $item->total_sold }} terjual</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-1.5">
                            <div class="bg-[#f15c32] h-1.5 rounded-full" style="width: {{ min(100, ($item->total_sold / ($bestSellers->first()->total_sold ?? 1)) * 100) }}%"></div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="py-10 text-center">
                    <p class="text-gray-400 text-sm font-medium">Belum ada data penjualan</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Aktivitas Terbaru -->
        <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="font-bold text-gray-900">Aktivitas Terbaru</h3>
                    <p class="text-xs text-gray-500 font-medium">5 pesanan terakhir masuk</p>
                </div>
                <a href="{{ route('orders') }}" class="text-xs font-bold text-red-500 hover:text-red-700 flex items-center">
                    Lihat semua <i data-lucide="arrow-right" class="w-3 h-3 ml-1"></i>
                </a>
            </div>

            <div class="space-y-3">
                @forelse($latestOrders as $order)
                <div class="flex items-center justify-between p-3 rounded-2xl {{ $order->status == 'pending' ? 'bg-gray-50 border border-gray-100' : 'border border-transparent hover:bg-white hover:border-gray-100' }} hover:shadow-sm transition-all cursor-pointer">
                    <div class="flex items-center">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($order->customer_name) }}&background=random" alt="{{ $order->customer_name }}" class="w-10 h-10 rounded-full border-2 border-white shadow-sm">
                        <div class="ml-3">
                            <p class="text-sm font-bold text-gray-900">{{ $order->customer_name }}</p>
                            <div class="flex items-center space-x-2 mt-0.5">
                                <span class="flex items-center text-[9px] font-bold {{ $order->notes == 'takeaway' ? 'text-orange-600 bg-orange-100 border-orange-200' : 'text-blue-600 bg-blue-50 border-blue-100' }} px-1.5 py-0.5 rounded border uppercase">
                                    <i data-lucide="{{ $order->notes == 'takeaway' ? 'shopping-bag' : 'utensils' }}" class="w-2.5 h-2.5 mr-1"></i> {{ ucfirst($order->notes) }}
                                </span>
                                <span class="flex items-center text-[9px] font-bold {{ $order->status == 'completed' ? 'text-green-700 bg-green-50 border-green-200' : 'text-yellow-700 bg-yellow-100 border-yellow-200' }} px-1.5 py-0.5 rounded border uppercase">
                                    <div class="w-1.5 h-1.5 rounded-full {{ $order->status == 'completed' ? 'bg-green-500' : 'bg-yellow-500' }} mr-1"></div> {{ $order->status == 'completed' ? 'Selesai' : 'Pending' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-extrabold text-red-500 mb-0.5">#{{ substr($order->order_number, -4) }}</p>
                        <p class="text-xs font-bold text-gray-900 mb-0.5">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                        <p class="text-[10px] text-gray-400 font-medium">{{ $order->created_at->format('H:i') }}</p>
                    </div>
                </div>
                @empty
                <div class="py-10 text-center">
                    <p class="text-gray-400 text-sm font-medium">Belum ada aktivitas hari ini</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('salesChart').getContext('2d');
    
    // Gradient for line chart
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(234, 88, 12, 0.2)');
    gradient.addColorStop(1, 'rgba(234, 88, 12, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData->pluck('date')) !!},
            datasets: [{
                label: 'Pendapatan',
                data: {!! json_encode($chartData->pluck('revenue')) !!}, // Scale represented in millions
                borderColor: '#ea580c',
                backgroundColor: gradient,
                borderWidth: 2,
                pointBackgroundColor: '#ea580c',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4
            }, {
                label: 'Pesanan',
                data: {!! json_encode($chartData->pluck('count')) !!},
                borderColor: '#fca5a5', // Lighter red/orange
                borderWidth: 2,
                borderDash: [5, 5],
                pointBackgroundColor: '#fca5a5',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 3,
                fill: false,
                tension: 0.4,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#fff',
                    titleColor: '#111827',
                    bodyColor: '#4b5563',
                    borderColor: '#f3f4f6',
                    borderWidth: 1,
                    padding: 10,
                    boxPadding: 4,
                    usePointStyle: true,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                if(context.datasetIndex === 0) {
                                    label += 'Rp ' + context.parsed.y + 'jt';
                                } else {
                                    label += context.parsed.y;
                                }
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        color: '#9ca3af',
                        font: {
                            size: 11,
                            family: "'Inter', sans-serif"
                        }
                    }
                },
                y: {
                    min: 0,
                    max: 5.0,
                    grid: {
                        color: '#f3f4f6',
                        drawBorder: false
                    },
                    ticks: {
                        stepSize: 1.0,
                        color: '#9ca3af',
                        font: {
                            size: 11,
                            family: "'Inter', sans-serif"
                        },
                        callback: function(value) {
                            return value + 'jt';
                        }
                    }
                },
                y1: {
                    position: 'right',
                    min: 0,
                    max: 30,
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        stepSize: 5,
                        color: '#9ca3af',
                        font: {
                            size: 11,
                            family: "'Inter', sans-serif"
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
        }
    });
});
</script>
@endsection
