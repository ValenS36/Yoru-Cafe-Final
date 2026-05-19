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
        $cart = json_decode($request->cart, true);
        if (!$cart || !is_array($cart) || count($cart) < 1) {
            return response()->json(['success' => false, 'message' => 'Keranjang tidak valid'], 422);
        }

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'order_type' => 'required|string',
            'payment_method' => 'required|string',
            'payment_proof' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        try {
            return DB::transaction(function () use ($request, $cart) {
                $subtotal = 0;
                foreach ($cart as $item) {
                    $subtotal += $item['price'] * $item['quantity'];
                }

                $tax = $subtotal * 0.10;
                $total = $subtotal + $tax;

                $order = Order::create([
                    'user_id' => Auth::id(),
                    'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
                    'customer_name' => $request->customer_name,
                    'total' => $total,
                    'status' => 'pending',
                    'payment_method' => $request->payment_method,
                    'payment_status' => 'paid',
                    'notes' => $request->order_type,
                ]);

                foreach ($cart as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'menu_id' => $item['id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['price'] * $item['quantity'],
                    ]);
                }

                $proofPath = null;
                if ($request->hasFile('payment_proof')) {
                    $proofPath = $request->file('payment_proof')->store('payments', 'public');
                }

                Payment::create([
                    'order_id' => $order->id,
                    'method' => $request->payment_method,
                    'amount' => $total,
                    'change' => 0,
                    'payment_proof' => $proofPath,
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
