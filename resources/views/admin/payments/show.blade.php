@extends('layouts.admin')

@section('title', 'Detail Konfirmasi Pembayaran')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.payments.index') }}" class="text-indigo-600 hover:text-indigo-800">&larr; Kembali</a>
</div>

<h2 class="text-2xl font-bold mb-6">Detail Konfirmasi Pembayaran</h2>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Order Info -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold mb-4">Informasi Order</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Order Number</p>
                    <p class="font-semibold">{{ $paymentConfirmation->order->order_number }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Order Total</p>
                    <p class="font-semibold">Rp {{ number_format($paymentConfirmation->order->total_amount, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Customer</p>
                    <p class="font-semibold">{{ $paymentConfirmation->user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $paymentConfirmation->user->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Order Date</p>
                    <p class="font-semibold">{{ $paymentConfirmation->order->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold mb-4">Informasi Transfer</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Bank Tujuan</p>
                    <p class="font-semibold text-lg">{{ $paymentConfirmation->bank_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Jumlah Transfer</p>
                    <p class="font-semibold text-lg text-indigo-600">Rp {{ number_format($paymentConfirmation->amount, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Nama Pengirim</p>
                    <p class="font-semibold">{{ $paymentConfirmation->account_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">No. Rekening Pengirim</p>
                    <p class="font-semibold">{{ $paymentConfirmation->account_number }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-sm text-gray-500">Tanggal Upload</p>
                    <p class="font-semibold">{{ $paymentConfirmation->confirmed_at->format('d M Y H:i:s') }}</p>
                </div>
                @if($paymentConfirmation->notes)
                    <div class="col-span-2">
                        <p class="text-sm text-gray-500">Catatan Customer</p>
                        <p class="text-gray-700 p-3 bg-gray-50 rounded">{{ $paymentConfirmation->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Proof Image -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold mb-4">Bukti Transfer</h3>
            <div class="border rounded-lg overflow-hidden">
                <img src="{{ Storage::url($paymentConfirmation->proof_image) }}" 
                     alt="Bukti Transfer" 
                     class="w-full h-auto">
            </div>
            <a href="{{ Storage::url($paymentConfirmation->proof_image) }}" 
               target="_blank" 
               class="mt-3 inline-block text-indigo-600 hover:text-indigo-800">
                📥 Download Bukti Transfer
            </a>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold mb-4">Item Pesanan</h3>
            <table class="w-full">
                <thead class="border-b">
                    <tr>
                        <th class="text-left py-2">Produk</th>
                        <th class="text-right py-2">Harga</th>
                        <th class="text-right py-2">Qty</th>
                        <th class="text-right py-2">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($paymentConfirmation->order->items as $item)
                        <tr class="border-b">
                            <td class="py-3">{{ $item->product->name }}</td>
                            <td class="text-right py-3">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="text-right py-3">{{ $item->quantity }}</td>
                            <td class="text-right py-3 font-semibold">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right py-4 font-bold">Total:</td>
                        <td class="text-right py-4 font-bold text-lg">Rp {{ number_format($paymentConfirmation->order->total_amount, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Sidebar -->
    <div>
        <!-- Status Card -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-xl font-bold mb-4">Status</h3>
            <div class="text-center mb-4">
                <span class="inline-block px-4 py-2 rounded-full text-lg font-semibold
                    {{ $paymentConfirmation->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $paymentConfirmation->status == 'approved' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $paymentConfirmation->status == 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                    {{ ucfirst($paymentConfirmation->status) }}
                </span>
            </div>

            @if($paymentConfirmation->status == 'pending')
                <!-- Approve Form -->
                <form method="POST" action="{{ route('admin.payments.approve', $paymentConfirmation) }}" class="mb-3">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Admin (Optional)</label>
                        <textarea name="admin_notes" rows="3" class="w-full rounded-md border-gray-300 text-sm" placeholder="Catatan untuk customer..."></textarea>
                    </div>
                    <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 font-semibold">
                        ✓ Approve Pembayaran
                    </button>
                </form>

                <!-- Reject Form -->
                <form method="POST" action="{{ route('admin.payments.reject', $paymentConfirmation) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan (Required)</label>
                        <textarea name="admin_notes" rows="3" class="w-full rounded-md border-gray-300 text-sm" placeholder="Jelaskan alasan penolakan..." required></textarea>
                    </div>
                    <button type="submit" class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 font-semibold">
                        ✗ Reject Pembayaran
                    </button>
                </form>
            @else
                <!-- Verification Info -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600">Verified at:</p>
                    <p class="font-semibold">{{ $paymentConfirmation->verified_at->format('d M Y H:i') }}</p>
                    
                    @if($paymentConfirmation->verifiedBy)
                        <p class="text-sm text-gray-600 mt-2">Verified by:</p>
                        <p class="font-semibold">{{ $paymentConfirmation->verifiedBy->name }}</p>
                    @endif

                    @if($paymentConfirmation->admin_notes)
                        <p class="text-sm text-gray-600 mt-2">Admin Notes:</p>
                        <p class="text-gray-700 text-sm">{{ $paymentConfirmation->admin_notes }}</p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Validation Helper -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h4 class="font-semibold text-blue-900 mb-2">✓ Checklist Verifikasi</h4>
            <ul class="text-sm text-blue-800 space-y-1">
                <li>☐ Jumlah transfer sesuai</li>
                <li>☐ Bukti transfer valid</li>
                <li>☐ Nama pengirim sesuai</li>
                <li>☐ Bank tujuan benar</li>
            </ul>
        </div>
    </div>
</div>
@endsection