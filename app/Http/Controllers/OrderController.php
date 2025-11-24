<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;

class OrderController extends BaseController
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of customer's orders
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('items.product')
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        // Pastikan user hanya bisa lihat ordernya sendiri
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized - This is not your order');
        }

        // Load relationships
        $order->load(['items.product.category', 'user']);
        
        return view('orders.show', compact('order'));
    }
}