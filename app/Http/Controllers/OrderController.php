<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Order;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();
        
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->status && $request->status != 'Semua Status') {
            $query->where('status', strtolower($request->status));
        }

        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $orders = $query->latest()->paginate(10);
        
        $stats = [
            'pending' => Order::where('status', 'pending')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'total_today' => Order::whereDate('created_at', today())->sum('total')
        ];

        return view('orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        $order->load(['items.menu', 'user']);
        return view('orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,completed'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function receipt(Request $request)
    {
        $orderId = $request->query('order_id');
        
        if (!$orderId) {
            return back()->with('error', 'Silakan masukkan Order ID.');
        }

        // Clean ID (remove # if present)
        $cleanId = str_replace('#', '', $orderId);

        // Search by full order_number or last part of it
        $order = Order::where('order_number', 'like', '%' . $cleanId)->first();

        if (!$order) {
            return back()->with('error', 'Pesanan dengan ID ' . $orderId . ' tidak ditemukan.');
        }

        return view('orders.receipt', compact('order'));
    }
}
