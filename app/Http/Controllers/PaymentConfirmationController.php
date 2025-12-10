<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller as BaseController;

class PaymentConfirmationController extends BaseController
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Show form untuk upload bukti transfer
     */
    public function create(Order $order)
    {
        // Security check
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Check apakah order sudah punya payment confirmation
        if ($order->paymentConfirmation) {
            return redirect()->route('orders.show', $order)
                ->with('info', 'Anda sudah upload bukti pembayaran untuk order ini');
        }

        // Check apakah payment method adalah transfer bank
        if ($order->payment_method !== 'bank_transfer') {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Konfirmasi pembayaran hanya untuk metode Transfer Bank');
        }

        return view('payment.create', compact('order'));
    }

    /**
     * Store payment confirmation
     */
    public function store(Request $request, Order $order)
    {
        // Security check
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Validation
        $request->validate([
            'bank_name' => 'required|string|in:BCA,Mandiri,BNI',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'amount' => 'required|numeric|min:0',
            'proof_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'notes' => 'nullable|string|max:500',
        ]);

        // Upload proof image
        $proofPath = $request->file('proof_image')->store('payment-proofs', 'public');

        // Create payment confirmation
        $paymentConfirmation = PaymentConfirmation::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'bank_name' => $request->bank_name,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
            'amount' => $request->amount,
            'proof_image' => $proofPath,
            'notes' => $request->notes,
            'status' => 'pending',
            'confirmed_at' => now(),
        ]);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Bukti pembayaran berhasil diupload! Menunggu verifikasi admin.');
    }

    /**
     * Show payment confirmation detail
     */
    public function show(Order $order)
    {
        // Security check
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $paymentConfirmation = $order->paymentConfirmation;

        if (!$paymentConfirmation) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Belum ada konfirmasi pembayaran untuk order ini');
        }

        return view('payment.show', compact('order', 'paymentConfirmation'));
    }
}