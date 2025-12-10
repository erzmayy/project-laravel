<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Shipping;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function create(Order $order)
    {
        if ($order->shipping) {
            return redirect()->back()->with('error', 'Shipping sudah dibuat untuk order ini');
        }

        return view('admin.shipping.create', compact('order'));
    }

    public function store(Request $request, Order $order)
    {
        $request->validate([
            'courier' => 'required|string',
            'service' => 'required|string',
            'cost' => 'required|numeric|min:0',
            'tracking_number' => 'nullable|string',
            'estimated_days' => 'nullable|integer',
        ]);

        $shipping = Shipping::create([
            'order_id' => $order->id,
            'courier' => $request->courier,
            'service' => $request->service,
            'cost' => $request->cost,
            'tracking_number' => $request->tracking_number,
            'estimated_days' => $request->estimated_days,
            'status' => 'pending',
        ]);

        // Update order status
        $order->update(['status' => 'processing']);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Shipping berhasil dibuat!');
    }

    public function update(Request $request, Shipping $shipping)
    {
        $request->validate([
            'status' => 'required|in:pending,picked_up,in_transit,delivered,returned',
            'tracking_number' => 'nullable|string',
            'delivery_notes' => 'nullable|string',
        ]);

        $data = [
            'status' => $request->status,
            'tracking_number' => $request->tracking_number,
            'delivery_notes' => $request->delivery_notes,
        ];

        // Set timestamp berdasarkan status
        if ($request->status === 'picked_up' && !$shipping->shipped_at) {
            $data['shipped_at'] = now();
        }

        if ($request->status === 'delivered' && !$shipping->delivered_at) {
            $data['delivered_at'] = now();
            // Update order status
            $shipping->order->update(['status' => 'completed']);
        }

        $shipping->update($data);

        return redirect()->back()->with('success', 'Status pengiriman berhasil diupdate!');
    }
}