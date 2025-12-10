@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="text-gray-500 text-sm mb-2">Total Produk</div>
        <div class="text-3xl font-bold text-indigo-600">{{ $totalProducts }}</div>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="text-gray-500 text-sm mb-2">Total Pesanan</div>
        <div class="text-3xl font-bold text-green-600">{{ $totalOrders }}</div>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="text-gray-500 text-sm mb-2">Total Pendapatan</div>
        <div class="text-3xl font-bold text-purple-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="text-gray-500 text-sm mb-2">Total Customer</div>
        <div class="text-3xl font-bold text-orange-600">{{ $totalUsers }}</div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-xl font-bold mb-4">Pesanan Terbaru</h3>
    <table class="w-full">
        <thead>
            <tr class="border-b">
                <th class="text-left py-3">No. Pesanan</th>
                <th class="text-left py-3">Customer</th>
                <th class="text-left py-3">Total</th>
                <th class="text-left py-3">Status</th>
                <th class="text-left py-3">Tanggal</th>
            </tr>
        </thead> 
        <tbody>
            @foreach($recentOrders as $order)
                <tr class="border-b">
                    <td class="py-3">{{ $order->order_number }}</td>
                    <td class="py-3">{{ $order->user->name }}</td>
                    <td class="py-3">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    <td class="py-3">
                        <span class="px-2 py-1 text-xs rounded-full {{ $order->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="py-3">{{ $order->created_at->format('d M Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection