<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentConfirmation;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentConfirmationController extends Controller
{
    /**
     * List semua payment confirmations
     */
    public function index(Request $request)
    {
        $status = $request->status ?? 'pending';

        $confirmations = PaymentConfirmation::with(['order', 'user'])
            ->where('status', $status)
            ->latest()
            ->paginate(10);

        return view('admin.payments.index', compact('confirmations', 'status'));
    }

    /**
     * Show detail payment confirmation
     */
    public function show(PaymentConfirmation $paymentConfirmation)
    {
        $paymentConfirmation->load(['order.items.product', 'user', 'verifiedBy']);
        
        return view('admin.payments.show', compact('paymentConfirmation'));
    }

    /**
     * Approve payment confirmation
     */
    public function approve(Request $request, PaymentConfirmation $paymentConfirmation)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:500',
        ]);

        // Update payment confirmation
        $paymentConfirmation->update([
            'status' => 'approved',
            'verified_at' => now(),
            'verified_by' => Auth::id(),
            'admin_notes' => $request->admin_notes,
        ]);

        // Update order status ke "paid"
        $paymentConfirmation->order->update([
            'status' => 'paid',
        ]);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Pembayaran berhasil diapprove! Order status updated ke Paid.');
    }

    /**
     * Reject payment confirmation
     */
    public function reject(Request $request, PaymentConfirmation $paymentConfirmation)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:500',
        ]);

        $paymentConfirmation->update([
            'status' => 'rejected',
            'verified_at' => now(),
            'verified_by' => Auth::id(),
            'admin_notes' => $request->admin_notes,
        ]);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Pembayaran ditolak. Customer akan melihat notifikasi.');
    }
}