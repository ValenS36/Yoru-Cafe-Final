<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $filterType = $request->get('filter_type', 'monthly');
        $categoryId = $request->get('category_id', '');
        
        if ($filterType === 'daily') {
            $filterValue = $request->get('filter_value', date('Y-m-d'));
            $startDate = Carbon::parse($filterValue)->startOfDay();
            $endDate = Carbon::parse($filterValue)->endOfDay();
        } elseif ($filterType === 'weekly') {
            $filterValue = $request->get('filter_value', date('Y-\WW'));
            $startDate = Carbon::parse($filterValue)->startOfWeek();
            $endDate = Carbon::parse($filterValue)->endOfWeek();
        } elseif ($filterType === 'yearly') {
            $filterValue = $request->get('filter_value', date('Y'));
            $startDate = Carbon::createFromDate($filterValue, 1, 1)->startOfYear();
            $endDate = Carbon::createFromDate($filterValue, 1, 1)->endOfYear();
        } elseif ($filterType === 'custom') {
            $filterValue = '';
            $customStartDate = $request->get('start_date', date('Y-m-01'));
            $customEndDate = $request->get('end_date', date('Y-m-t'));
            $startDate = Carbon::parse($customStartDate)->startOfDay();
            $endDate = Carbon::parse($customEndDate)->endOfDay();
        } else {
            $filterType = 'monthly';
            $filterValue = $request->get('filter_value', date('Y-m'));
            $startDate = Carbon::parse($filterValue)->startOfMonth();
            $endDate = Carbon::parse($filterValue)->endOfMonth();
        }
        
        $customStartDate = $customStartDate ?? date('Y-m-01');
        $customEndDate = $customEndDate ?? date('Y-m-t');

        $categories = Category::all();

        // Stats for the month
        if ($categoryId) {
            $baseQuery = OrderItem::whereHas('order', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            })->whereHas('menu', function($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });

            $totalRevenue = $baseQuery->sum(DB::raw('price * quantity'));
            $totalOrdersOrItems = $baseQuery->sum('quantity');
            $averageOrder = $totalOrdersOrItems > 0 ? ($totalRevenue / $totalOrdersOrItems) : 0;
            
            $completedOrdersQuery = clone $baseQuery;
            $completedOrders = $completedOrdersQuery->whereHas('order', function($q) {
                $q->where('status', 'completed');
            })->sum('quantity');

        } else {
            $totalRevenue = Order::whereBetween('created_at', [$startDate, $endDate])->sum('total');
            $totalOrdersOrItems = Order::whereBetween('created_at', [$startDate, $endDate])->count();
            $averageOrder = Order::whereBetween('created_at', [$startDate, $endDate])->avg('total') ?? 0;
            $completedOrders = Order::whereBetween('created_at', [$startDate, $endDate])->where('status', 'completed')->count();
        }

        $stats = [
            'total_revenue' => $totalRevenue,
            'total_orders' => $totalOrdersOrItems,
            'average_order' => $averageOrder,
            'completed_orders' => $completedOrders,
        ];

        // Sales trend
        $driver = DB::connection()->getDriverName();
        if ($filterType === 'daily') {
            $dateExpr = $driver === 'sqlite' ? "strftime('%H:00', created_at)" : "DATE_FORMAT(created_at, '%H:00')";
        } elseif ($filterType === 'yearly') {
            $dateExpr = $driver === 'sqlite' ? "strftime('%Y-%m', created_at)" : "DATE_FORMAT(created_at, '%Y-%m')";
        } else {
            $dateExpr = $driver === 'sqlite' ? "date(created_at)" : "DATE(created_at)";
        }

        if ($categoryId) {
            $trendSales = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('menus', 'order_items.menu_id', '=', 'menus.id')
                ->select(
                    DB::raw(str_replace('created_at', 'orders.created_at', $dateExpr) . " as date_group"),
                    DB::raw('SUM(order_items.price * order_items.quantity) as revenue'),
                    DB::raw('SUM(order_items.quantity) as orders')
                )
                ->whereBetween('orders.created_at', [$startDate, $endDate])
                ->where('menus.category_id', $categoryId)
                ->groupBy('date_group')
                ->orderBy('date_group')
                ->get();
        } else {
            $trendSales = Order::select(
                DB::raw("$dateExpr as date_group"),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date_group')
            ->orderBy('date_group')
            ->get();
        }

        // Top selling products for the month
        $topQuery = OrderItem::select('menu_id', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(price * quantity) as total_revenue'))
            ->whereHas('order', function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            });
            
        if ($categoryId) {
            $topQuery->whereHas('menu', function($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }

        $topProducts = $topQuery->with('menu')
            ->groupBy('menu_id')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();

        return view('reports.index', compact('stats', 'trendSales', 'topProducts', 'filterType', 'filterValue', 'startDate', 'endDate', 'categories', 'categoryId', 'customStartDate', 'customEndDate'));
    }

    public function export(Request $request)
    {
        $filterType = $request->get('filter_type', 'monthly');
        $categoryId = $request->get('category_id', '');
        
        if ($filterType === 'daily') {
            $filterValue = $request->get('filter_value', date('Y-m-d'));
            $startDate = Carbon::parse($filterValue)->startOfDay();
            $endDate = Carbon::parse($filterValue)->endOfDay();
            $periodLabel = Carbon::parse($filterValue)->translatedFormat('d F Y');
            $colHeader = 'Jam';
        } elseif ($filterType === 'weekly') {
            $filterValue = $request->get('filter_value', date('Y-\WW'));
            $startDate = Carbon::parse($filterValue)->startOfWeek();
            $endDate = Carbon::parse($filterValue)->endOfWeek();
            $periodLabel = $startDate->translatedFormat('d M Y') . ' - ' . $endDate->translatedFormat('d M Y');
            $colHeader = 'Tanggal';
        } elseif ($filterType === 'yearly') {
            $filterValue = $request->get('filter_value', date('Y'));
            $startDate = Carbon::createFromDate($filterValue, 1, 1)->startOfYear();
            $endDate = Carbon::createFromDate($filterValue, 1, 1)->endOfYear();
            $periodLabel = 'Tahun ' . $filterValue;
            $colHeader = 'Bulan';
        } elseif ($filterType === 'custom') {
            $filterValue = 'custom';
            $customStartDate = $request->get('start_date', date('Y-m-01'));
            $customEndDate = $request->get('end_date', date('Y-m-t'));
            $startDate = Carbon::parse($customStartDate)->startOfDay();
            $endDate = Carbon::parse($customEndDate)->endOfDay();
            $periodLabel = $startDate->translatedFormat('d M Y') . ' - ' . $endDate->translatedFormat('d M Y');
            $colHeader = 'Tanggal';
        } else {
            $filterType = 'monthly';
            $filterValue = $request->get('filter_value', date('Y-m'));
            $startDate = Carbon::parse($filterValue)->startOfMonth();
            $endDate = Carbon::parse($filterValue)->endOfMonth();
            $periodLabel = Carbon::parse($filterValue)->translatedFormat('F Y');
            $colHeader = 'Tanggal';
        }

        $driver = DB::connection()->getDriverName();
        if ($filterType === 'daily') {
            $dateExpr = $driver === 'sqlite' ? "strftime('%H:00', created_at)" : "DATE_FORMAT(created_at, '%H:00')";
        } elseif ($filterType === 'yearly') {
            $dateExpr = $driver === 'sqlite' ? "strftime('%Y-%m', created_at)" : "DATE_FORMAT(created_at, '%Y-%m')";
        } else {
            $dateExpr = $driver === 'sqlite' ? "date(created_at)" : "DATE(created_at)";
        }

        // Summary
        if ($categoryId) {
            $trendSales = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('menus', 'order_items.menu_id', '=', 'menus.id')
                ->select(
                    DB::raw(str_replace('created_at', 'orders.created_at', $dateExpr) . " as date_group"),
                    DB::raw('SUM(order_items.price * order_items.quantity) as revenue'),
                    DB::raw('SUM(order_items.quantity) as orders')
                )
                ->whereBetween('orders.created_at', [$startDate, $endDate])
                ->where('menus.category_id', $categoryId)
                ->groupBy('date_group')
                ->orderBy('date_group')
                ->get();
        } else {
            $trendSales = Order::select(
                DB::raw("$dateExpr as date_group"),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date_group')
            ->orderBy('date_group')
            ->get();
        }

        $filename = "Laporan_Penjualan_" . $filterType . "_" . $filterValue . ".xlsx";
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Penjualan');

        // Protect the sheet
        $protection = $sheet->getProtection();
        $protection->setPassword('YoruCafe2026!');
        $protection->setSheet(true);

        $row = 1;
        $sheet->setCellValue("A{$row}", "LAPORAN PENJUALAN: " . strtoupper($periodLabel));
        $sheet->getStyle("A{$row}")->getFont()->setBold(true)->setSize(14);
        $row++;

        if ($categoryId) {
            $category = Category::find($categoryId);
            $sheet->setCellValue("A{$row}", "KATEGORI: " . strtoupper($category ? $category->name : ''));
            $sheet->getStyle("A{$row}")->getFont()->setBold(true);
            $row++;
        }
        $row++;

        // Section 2: Ringkasan Tren (Harian/Bulanan/Tahunan)
        $sheet->setCellValue("A{$row}", "RINGKASAN TREN");
        $sheet->getStyle("A{$row}")->getFont()->setBold(true);
        $row++;

        $sheet->setCellValue("A{$row}", $colHeader);
        $sheet->setCellValue("B{$row}", $categoryId ? "Total Item Terjual" : "Total Pesanan");
        $sheet->setCellValue("C{$row}", "Total Pendapatan");
        $sheet->getStyle("A{$row}:C{$row}")->getFont()->setBold(true);
        $row++;
        
        $totalOrders = 0;
        $totalRevenue = 0;
        
        foreach ($trendSales as $sale) {
            $sheet->setCellValue("A{$row}", $sale->date_group);
            $sheet->setCellValue("B{$row}", $sale->orders);
            $sheet->setCellValue("C{$row}", $sale->revenue);
            $totalRevenue += $sale->revenue;
            $totalOrders += $sale->orders;
            $row++;
        }
        
        $sheet->setCellValue("A{$row}", "TOTAL KESELURUHAN");
        $sheet->setCellValue("B{$row}", $totalOrders);
        $sheet->setCellValue("C{$row}", $totalRevenue);
        $sheet->getStyle("A{$row}:C{$row}")->getFont()->setBold(true);
        $row += 3;

        // Section 3: Detail Transaksi
        $sheet->setCellValue("A{$row}", "DETAIL TRANSAKSI");
        $sheet->getStyle("A{$row}")->getFont()->setBold(true);
        $row++;
        
        if ($categoryId) {
            $sheet->setCellValue("A{$row}", 'No. Pesanan');
            $sheet->setCellValue("B{$row}", 'Tanggal');
            $sheet->setCellValue("C{$row}", 'Item');
            $sheet->setCellValue("D{$row}", 'Harga Satuan');
            $sheet->setCellValue("E{$row}", 'Qty');
            $sheet->setCellValue("F{$row}", 'Subtotal');
            $sheet->setCellValue("G{$row}", 'Kasir');
            $sheet->getStyle("A{$row}:G{$row}")->getFont()->setBold(true);
            $row++;

            $items = OrderItem::with(['order.user', 'menu'])
                ->whereHas('order', function($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                })
                ->whereHas('menu', function($q) use ($categoryId) {
                    $q->where('category_id', $categoryId);
                })
                ->get();
                
            foreach ($items as $item) {
                $sheet->setCellValue("A{$row}", $item->order->order_number ?? '-');
                $sheet->setCellValue("B{$row}", $item->order->created_at->format('Y-m-d H:i'));
                $sheet->setCellValue("C{$row}", $item->menu->name ?? '-');
                $sheet->setCellValue("D{$row}", $item->price);
                $sheet->setCellValue("E{$row}", $item->quantity);
                $sheet->setCellValue("F{$row}", $item->price * $item->quantity);
                $sheet->setCellValue("G{$row}", $item->order->user->name ?? 'N/A');
                $row++;
            }
        } else {
            $sheet->setCellValue("A{$row}", 'No. Pesanan');
            $sheet->setCellValue("B{$row}", 'Tanggal');
            $sheet->setCellValue("C{$row}", 'Nama Pelanggan');
            $sheet->setCellValue("D{$row}", 'Jumlah Item');
            $sheet->setCellValue("E{$row}", 'Total');
            $sheet->setCellValue("F{$row}", 'Metode Pembayaran');
            $sheet->setCellValue("G{$row}", 'Status');
            $sheet->setCellValue("H{$row}", 'Staff');
            $sheet->getStyle("A{$row}:H{$row}")->getFont()->setBold(true);
            $row++;

            $orders = Order::whereBetween('created_at', [$startDate, $endDate])
                ->with(['payment', 'user'])
                ->orderBy('created_at', 'desc')
                ->get();
                
            foreach ($orders as $order) {
                $sheet->setCellValue("A{$row}", $order->order_number);
                $sheet->setCellValue("B{$row}", $order->created_at->format('Y-m-d H:i'));
                $sheet->setCellValue("C{$row}", $order->customer_name);
                $sheet->setCellValue("D{$row}", $order->items->sum('quantity'));
                $sheet->setCellValue("E{$row}", $order->total);
                $sheet->setCellValue("F{$row}", ucfirst($order->payment_method));
                $sheet->setCellValue("G{$row}", ucfirst($order->status));
                $sheet->setCellValue("H{$row}", $order->user->name ?? 'N/A');
                $row++;
            }
        }

        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $headers = [
            "Content-Type"        => "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            "Content-Disposition" => "attachment; filename=\"$filename\"",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        return response()->stream(function() use ($spreadsheet) {
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
        }, 200, $headers);
    }
}
