@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('header')
<header class="flex items-start justify-between bg-transparent pb-2 max-w-[1400px] mx-auto w-full mt-2">
    <!-- Left side -->
    <div>
        <div class="flex items-center text-[11px] font-semibold text-gray-400 tracking-wide uppercase mb-1">
            <span class="text-[#ea580c]">YORUCAFE</span>
            <i data-lucide="chevron-right" class="w-3 h-3 mx-1"></i>
            <span>Laporan Penjualan</span>
        </div>
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
            Laporan Penjualan
        </h2>
        <p class="text-xs font-medium text-gray-500 mt-1">Analisis kinerja bisnis Anda bulan ini</p>
    </div>

    <!-- Right side -->
    <div class="flex items-center space-x-4">
        <!-- Filter -->
        <form action="{{ route('reports') }}" method="GET" id="filterForm" class="flex items-center space-x-2" 
              x-data="{ filterType: '{{ $filterType }}', initType: '{{ $filterType }}' }"
              x-init="$watch('filterType', val => { if(val !== 'custom' && val !== initType) { $nextTick(() => { document.getElementById('filterForm').submit(); }); } })">
            <select name="filter_type" x-model="filterType" class="border-transparent bg-white rounded-xl py-2.5 pl-3 pr-8 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-primary shadow-sm">
                <option value="daily">Harian</option>
                <option value="weekly">Mingguan</option>
                <option value="monthly">Bulanan</option>
                <option value="yearly">Tahunan</option>
                <option value="custom">Custom (Rentang)</option>
            </select>
            
            <div class="relative flex items-center space-x-2">
                <div x-show="filterType !== 'custom'" class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                    <i data-lucide="calendar" class="h-4 w-4 text-gray-400"></i>
                </div>
                
                <!-- Daily -->
                <input x-show="filterType === 'daily'" type="date" name="filter_value" value="{{ $filterType === 'daily' ? $filterValue : date('Y-m-d') }}" onchange="document.getElementById('filterForm').submit()"
                       :disabled="filterType !== 'daily'" class="block w-40 pl-10 pr-3 py-2.5 border-transparent bg-white rounded-xl leading-5 text-gray-900 font-bold focus:outline-none focus:ring-2 focus:ring-primary shadow-sm sm:text-sm">
                
                <!-- Weekly -->
                <input x-show="filterType === 'weekly'" type="week" name="filter_value" value="{{ $filterType === 'weekly' ? $filterValue : date('Y-\WW') }}" onchange="document.getElementById('filterForm').submit()"
                       :disabled="filterType !== 'weekly'" class="block w-40 pl-10 pr-3 py-2.5 border-transparent bg-white rounded-xl leading-5 text-gray-900 font-bold focus:outline-none focus:ring-2 focus:ring-primary shadow-sm sm:text-sm">
                
                <!-- Monthly -->
                <input x-show="filterType === 'monthly'" type="month" name="filter_value" value="{{ $filterType === 'monthly' ? $filterValue : date('Y-m') }}" onchange="document.getElementById('filterForm').submit()"
                       :disabled="filterType !== 'monthly'" class="block w-40 pl-10 pr-3 py-2.5 border-transparent bg-white rounded-xl leading-5 text-gray-900 font-bold focus:outline-none focus:ring-2 focus:ring-primary shadow-sm sm:text-sm">
                
                <!-- Yearly -->
                <select x-show="filterType === 'yearly'" name="filter_value" onchange="document.getElementById('filterForm').submit()"
                        :disabled="filterType !== 'yearly'" class="block w-32 pl-10 pr-3 py-2.5 border-transparent bg-white rounded-xl leading-5 text-gray-900 font-bold focus:outline-none focus:ring-2 focus:ring-primary shadow-sm sm:text-sm">
                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" {{ ($filterType === 'yearly' && $filterValue == $y) ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>

                <!-- Custom -->
                <div x-show="filterType === 'custom'" class="flex items-center space-x-2">
                    <input type="date" name="start_date" value="{{ $customStartDate }}" :disabled="filterType !== 'custom'" class="block w-36 px-3 py-2.5 border-transparent bg-white rounded-xl leading-5 text-gray-900 font-bold focus:outline-none focus:ring-2 focus:ring-primary shadow-sm sm:text-sm">
                    <span class="text-gray-500 font-bold">-</span>
                    <input type="date" name="end_date" value="{{ $customEndDate }}" :disabled="filterType !== 'custom'" class="block w-36 px-3 py-2.5 border-transparent bg-white rounded-xl leading-5 text-gray-900 font-bold focus:outline-none focus:ring-2 focus:ring-primary shadow-sm sm:text-sm">
                    <button type="submit" class="bg-[#ea580c] hover:bg-orange-700 text-white px-4 py-2.5 rounded-xl font-bold text-sm shadow-sm transition-all">Terapkan</button>
                </div>
            </div>
            
            <select name="category_id" onchange="document.getElementById('filterForm').submit()" class="border-transparent bg-white rounded-xl py-2.5 pl-3 pr-8 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-primary shadow-sm">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </form>

        <!-- User Profile Pic -->
        <a href="{{ route('profile') }}" class="h-10 w-10 rounded-full border-2 border-white shadow-sm overflow-hidden bg-gray-200 cursor-pointer block hover:ring-2 hover:ring-[#ea580c] transition-all">
            <img src="{{ Auth::user()->image ? Storage::url(Auth::user()->image) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=ea580c&color=fff' }}" alt="User" class="w-full h-full object-cover">
        </a>
    </div>
</header>
@endsection

@section('content')
<div class="max-w-[1400px] mx-auto pb-8">
    
    <!-- Export Action -->
    <div class="flex justify-end mb-6">
        <a href="{{ route('reports.export', ['filter_type' => $filterType, 'filter_value' => $filterValue, 'category_id' => $categoryId, 'start_date' => $customStartDate, 'end_date' => $customEndDate]) }}" class="bg-[#1c1c1e] hover:bg-black text-white px-6 py-3 rounded-xl font-bold text-sm flex items-center shadow-sm transition-all transform hover:scale-[1.02]">
            <i data-lucide="file-down" class="w-4 h-4 mr-2"></i> Export Laporan</a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <!-- Revenue -->
        <div class="bg-white rounded-[24px] p-6 shadow-sm border border-gray-100">
            <div class="w-12 h-12 rounded-[14px] bg-green-50 flex items-center justify-center text-green-500 mb-4">
                <i data-lucide="banknote" class="w-6 h-6"></i>
            </div>
            <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Total Pendapatan</p>
            <div class="text-2xl font-extrabold text-gray-900 tracking-tight">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
            <p class="text-[10px] text-green-600 font-bold mt-2 flex items-center">
                <i data-lucide="trending-up" class="w-3 h-3 mr-1"></i> 
                @if($filterType === 'daily') Hari {{ \Carbon\Carbon::parse($filterValue)->translatedFormat('d M Y') }}
                @elseif($filterType === 'weekly' || $filterType === 'custom') {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M') }} - {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}
                @elseif($filterType === 'yearly') Tahun {{ $filterValue }}
                @else Bulan {{ \Carbon\Carbon::parse($filterValue)->translatedFormat('F Y') }}
                @endif
            </p>
        </div>

        <!-- Total Orders -->
        <div class="bg-white rounded-[24px] p-6 shadow-sm border border-gray-100">
            <div class="w-12 h-12 rounded-[14px] bg-blue-50 flex items-center justify-center text-blue-500 mb-4">
                <i data-lucide="{{ $categoryId ? 'shopping-bag' : 'shopping-cart' }}" class="w-6 h-6"></i>
            </div>
            <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">{{ $categoryId ? 'Total Item Terjual' : 'Total Pesanan' }}</p>
            <div class="text-2xl font-extrabold text-gray-900 tracking-tight">{{ $stats['total_orders'] }}</div>
            <p class="text-[10px] text-gray-400 font-bold mt-2">{{ $categoryId ? 'Kuantitas pesanan' : 'Termasuk pending & batal' }}</p>
        </div>

        <!-- Average Order -->
        <div class="bg-white rounded-[24px] p-6 shadow-sm border border-gray-100">
            <div class="w-12 h-12 rounded-[14px] bg-purple-50 flex items-center justify-center text-purple-500 mb-4">
                <i data-lucide="receipt" class="w-6 h-6"></i>
            </div>
            <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">{{ $categoryId ? 'Rata-rata Harga Item' : 'Rata-rata Pesanan' }}</p>
            <div class="text-2xl font-extrabold text-gray-900 tracking-tight">Rp {{ number_format($stats['average_order'], 0, ',', '.') }}</div>
            <p class="text-[10px] text-gray-400 font-bold mt-2">{{ $categoryId ? 'Pendapatan per item' : 'Per transaksi selesai' }}</p>
        </div>

        <!-- Completed Orders -->
        <div class="bg-white rounded-[24px] p-6 shadow-sm border border-gray-100">
            <div class="w-12 h-12 rounded-[14px] bg-orange-50 flex items-center justify-center text-[#ea580c] mb-4">
                <i data-lucide="check-circle" class="w-6 h-6"></i>
            </div>
            <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Pesanan Selesai</p>
            <div class="text-2xl font-extrabold text-gray-900 tracking-tight">{{ $stats['completed_orders'] }}</div>
            <p class="text-[10px] text-orange-600 font-bold mt-2">Tingkat keberhasilan: {{ $stats['total_orders'] > 0 ? round(($stats['completed_orders'] / $stats['total_orders']) * 100) : 0 }}%</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sales Trend Chart -->
        <div class="lg:col-span-2 bg-white rounded-[32px] p-8 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-lg font-extrabold text-gray-900">Tren Penjualan</h3>
                    <p class="text-xs font-medium text-gray-500">Performa pada 
                        @if($filterType === 'daily') {{ \Carbon\Carbon::parse($filterValue)->translatedFormat('d F Y') }}
                        @elseif($filterType === 'weekly' || $filterType === 'custom') {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M') }} - {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}
                        @elseif($filterType === 'yearly') {{ $filterValue }}
                        @else {{ \Carbon\Carbon::parse($filterValue)->translatedFormat('F Y') }}
                        @endif
                    </p>
                </div>
            </div>
            <div class="h-[350px] w-full">
                <canvas id="salesTrendChart"></canvas>
            </div>
        </div>

        <!-- Top Products -->
        <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100">
            <h3 class="text-lg font-extrabold text-gray-900 mb-6">Produk Terlaris</h3>
            <div class="space-y-6">
                @forelse($topProducts as $item)
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-2xl overflow-hidden bg-gray-50 border border-gray-100">
                            @if($item->menu->image)
                                <img src="{{ Str::startsWith($item->menu->image, 'http') ? $item->menu->image : Storage::url($item->menu->image) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <i data-lucide="image" class="w-5 h-5"></i>
                                </div>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">{{ $item->menu->name }}</p>
                            <p class="text-[10px] font-bold text-gray-400">{{ $item->total_sold }} terjual</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-extrabold text-gray-900">Rp {{ number_format($item->total_revenue / 1000, 1) }}rb</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-400 font-medium text-sm">Belum ada data penjualan</div>
                @endforelse
            </div>
            
            @if($topProducts->count() > 0)
            <div class="mt-8 pt-6 border-t border-gray-50">
                <button class="w-full py-3 rounded-xl bg-gray-50 text-gray-500 text-xs font-bold hover:bg-gray-100 transition-colors">Lihat Semua Produk</button>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesTrendChart').getContext('2d');
        
        const data = @json($trendSales);
        const filterType = '{{ $filterType }}';
        
        const labels = data.map(item => {
            if (filterType === 'daily') {
                return item.date_group; 
            } else if (filterType === 'yearly') {
                const parts = item.date_group.split('-');
                const date = new Date(parts[0], parts[1] - 1, 1);
                return date.toLocaleString('id-ID', { month: 'short' });
            } else {
                const date = new Date(item.date_group);
                return date.getDate() + ' ' + date.toLocaleString('id-ID', { month: 'short' });
            }
        });
        const revenues = data.map(item => item.revenue);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan',
                    data: revenues,
                    borderColor: '#ea580c',
                    backgroundColor: 'rgba(234, 88, 12, 0.05)',
                    borderWidth: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#ea580c',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1c1c1e',
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 12 },
                        padding: 12,
                        cornerRadius: 12,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.03)', drawBorder: false },
                        ticks: {
                            font: { size: 10, weight: 'bold' },
                            color: '#94a3b8',
                            callback: value => 'Rp ' + (value/1000) + 'rb'
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { size: 10, weight: 'bold' },
                            color: '#94a3b8'
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
