<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PosController extends Controller
{
    public function index()
    {
        $categories = Category::with('menus')->get();
        $menus = Menu::where('is_available', true)->get();
        return view('pos.index', compact('categories', 'menus'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'order_type' => 'required|string',
            'payment_method' => 'required|string',
            'cart' => 'required|array|min:1',
            'cart.*.id' => 'required|exists:menus,id',
            'cart.*.quantity' => 'required|integer|min:1',
            'cart.*.price' => 'required|numeric',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $subtotal = 0;
                foreach ($request->cart as $item) {
                    $subtotal += $item['price'] * $item['quantity'];
                }

                $tax = $subtotal * 0.10;
                $discount = $tax; // Mock discount logic from frontend
                $total = $subtotal + $tax - $discount;

                $order = Order::create([
                    'user_id' => Auth::id(),
                    'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
                    'customer_name' => $request->customer_name,
                    'total' => $total,
                    'status' => 'completed',
                    'payment_method' => $request->payment_method,
                    'payment_status' => 'paid',
                    'notes' => $request->order_type,
                ]);

                foreach ($request->cart as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'menu_id' => $item['id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['price'] * $item['quantity'],
                    ]);
                }

                Payment::create([
                    'order_id' => $order->id,
                    'method' => $request->payment_method,
                    'amount' => $total,
                    'change' => 0,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Order processed successfully',
                    'order_id' => $order->id
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process order: ' . $e->getMessage()
            ], 500);
        }
    }
}
