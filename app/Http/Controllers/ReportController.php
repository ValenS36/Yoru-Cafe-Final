<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', date('Y-m'));
        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();

        // Stats for the month
        $stats = [
            'total_revenue' => Order::whereBetween('created_at', [$startDate, $endDate])->sum('total'),
            'total_orders' => Order::whereBetween('created_at', [$startDate, $endDate])->count(),
            'average_order' => Order::whereBetween('created_at', [$startDate, $endDate])->avg('total') ?? 0,
            'completed_orders' => Order::whereBetween('created_at', [$startDate, $endDate])->where('status', 'completed')->count(),
        ];

        // Sales trend (Daily)
        $dailySales = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total) as revenue'),
            DB::raw('COUNT(*) as orders')
        )
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        // Top selling products for the month
        $topProducts = OrderItem::select('menu_id', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(price * quantity) as total_revenue'))
            ->whereHas('order', function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->with('menu')
            ->groupBy('menu_id')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();

        return view('reports.index', compact('stats', 'dailySales', 'topProducts', 'month'));
    }

    public function export(Request $request)
    {
        $month = $request->get('month', date('Y-m'));
        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();

        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->with(['payment', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = "Laporan_Penjualan_" . $month . ".csv";
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Order Number', 'Date', 'Customer', 'Items Count', 'Total', 'Payment Method', 'Status', 'Staff'];

        $callback = function() use($orders, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($orders as $order) {
                $row['Order Number'] = $order->order_number;
                $row['Date']         = $order->created_at->format('Y-m-d H:i');
                $row['Customer']     = $order->customer_name;
                $row['Items Count']  = $order->items->sum('quantity');
                $row['Total']        = $order->total;
                $row['Payment Method'] = $order->payment_method;
                $row['Status']       = $order->status;
                $row['Staff']        = $order->user->name ?? 'N/A';

                fputcsv($file, array_values($row));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
