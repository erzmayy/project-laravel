@extends('layouts.admin')

@section('title', 'Detail Pesanan')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.orders.index') }}" class="text-indigo-600 hover:text-indigo-900">&larr; Kembali</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-xl font-bold mb-4">Informasi Pesanan</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">No. Pesanan</p>
                    <p class="font-semibold">{{ $order->order_number }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tanggal</p>
                    <p class="font-semibold">{{ $order->created_at->format('d M Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Customer</p>
                    <p class="font-semibold">{{ $order->user->name }}</p>
                    <p class="text-sm">{{ $order->user->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Metode Pembayaran</p>
                    <p class="font-semibold">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
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
                    @foreach($order->items as $item)
                        <tr class="border-b">
                            <td class="py-3">
                                <div class="flex items-center">
                                    <div class="h-12 w-12 bg-gray-200 rounded mr-3">
                                        @if($item->product->images && count($item->product->images) > 0)
                                            <img src="{{ Storage::url($item->product->images[0]) }}" alt="{{ $item->product->name }}" class="h-12 w-12 object-cover rounded">
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold">{{ $item->product->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $item->product->category->name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="text-right py-3">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="text-right py-3">{{ $item->quantity }}</td>
                            <td class="text-right py-3 font-semibold">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right py-4 font-bold">Total:</td>
                        <td class="text-right py-4 font-bold text-lg text-indigo-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        @if($order->shipping)
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-xl font-bold mb-4">Informasi Pengiriman</h3>
        
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <p class="text-sm text-gray-500">Kurir</p>
                <p class="font-semibold">{{ $order->shipping->courier }} - {{ $order->shipping->service }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Ongkir</p>
                <p class="font-semibold">Rp {{ number_format($order->shipping->cost, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Nomor Resi</p>
                <p class="font-semibold">{{ $order->shipping->tracking_number ?: '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Status Pengiriman</p>
                <span class="px-2 py-1 text-xs rounded-full
                    {{ $order->shipping->status == 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $order->shipping->status == 'in_transit' ? 'bg-blue-100 text-blue-800' : '' }}
                    {{ $order->shipping->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                    {{ ucfirst($order->shipping->status) }}
                </span>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.shipping.update', $order->shipping) }}" class="border-t pt-4">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Update Status</label>
                    <select name="status" class="w-full rounded-md border-gray-300">
                        <option value="pending" {{ $order->shipping->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="picked_up" {{ $order->shipping->status == 'picked_up' ? 'selected' : '' }}>Picked Up</option>
                        <option value="in_transit" {{ $order->shipping->status == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                        <option value="delivered" {{ $order->shipping->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="returned" {{ $order->shipping->status == 'returned' ? 'selected' : '' }}>Returned</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Resi</label>
                    <input type="text" name="tracking_number" value="{{ $order->shipping->tracking_number }}" class="w-full rounded-md border-gray-300">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Pengiriman</label>
                    <textarea name="delivery_notes" rows="2" class="w-full rounded-md border-gray-300">{{ $order->shipping->delivery_notes }}</textarea>
                </div>
            </div>
            
            <button type="submit" class="mt-4 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                Update Shipping
            </button>
        </form>
    </div>
@else
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-xl font-bold mb-4">Pengiriman</h3>
        <p class="text-gray-600 mb-4">Belum ada informasi pengiriman untuk order ini</p>
        <a href="{{ route('admin.shipping.create', $order) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
            Buat Shipping
        </a>
    </div>
@endif

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold mb-4">Alamat Pengiriman</h3>
            <p class="font-semibold">{{ $order->shipping_name }}</p>
            <p class="text-gray-600">{{ $order->shipping_phone }}</p>
            <p class="text-gray-600 mt-2">{{ $order->shipping_address }}</p>
            @if($order->notes)
                <div class="mt-4 p-3 bg-gray-50 rounded">
                    <p class="text-sm font-semibold">Catatan:</p>
                    <p class="text-sm text-gray-600">{{ $order->notes }}</p>
                </div>
            @endif
        </div>
    </div>

    <div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold mb-4">Update Status</h3>
            <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Pesanan</label>
                    <select name="status" class="w-full rounded-md border-gray-300 shadow-sm">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700">
                    Update Status
                </button>
            </form>

            <div class="mt-6 p-4 bg-gray-50 rounded">
                <h4 class="font-semibold mb-2">Status Guide:</h4>
                <ul class="text-sm space-y-1 text-gray-600">
                    <li><span class="font-medium">Pending:</span> Menunggu pembayaran</li>
                    <li><span class="font-medium">Paid:</span> Pembayaran diterima</li>
                    <li><span class="font-medium">Processing:</span> Sedang diproses</li>
                    <li><span class="font-medium">Shipped:</span> Sedang dikirim</li>
                    <li><span class="font-medium">Completed:</span> Pesanan selesai</li>
                    <li><span class="font-medium">Cancelled:</span> Dibatalkan</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection