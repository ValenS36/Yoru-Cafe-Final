<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRevenue = Order::where('payment_status', 'paid')->whereDate('created_at', today())->sum('total');
        $ordersToday = Order::whereDate('created_at', today())->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        
        $latestOrders = Order::latest()->take(5)->get();
        
        $bestSellers = OrderItem::select('menu_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('menu_id')
            ->orderByDesc('total_sold')
            ->with('menu')
            ->take(5)
            ->get();

        // Chart data (last 7 days)
        $chartData = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function($item) {
                return [
                    'date' => date('d M', strtotime($item->date)),
                    'revenue' => (float)($item->revenue / 1000000), // In millions for chart
                    'count' => $item->count
                ];
            });

        return view('dashboard.index', compact(
            'totalRevenue', 
            'ordersToday', 
            'pendingOrders', 
            'latestOrders', 
            'bestSellers',
            'chartData'
        ));
    }
}
