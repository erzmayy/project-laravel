@extends('layouts.admin')

@section('title', 'Laporan Transaksi')

@section('content')
<h2 class="text-2xl font-bold mb-6">Laporan Transaksi</h2>

<!-- Filter Form -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form method="GET" action="{{ route('admin.reports.index') }}" class="flex items-end space-x-4">
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
            <input type="date" name="start_date" value="{{ $startDate }}" class="w-full rounded-md border-gray-300">
        </div>
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
            <input type="date" name="end_date" value="{{ $endDate }}" class="w-full rounded-md border-gray-300">
        </div>
        <div>
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
                Filter
            </button>
        </div>
        <div>
            <a href="{{ route('admin.reports.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}" 
               class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 inline-block">
                Export CSV
            </a>
        </div>
    </form>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="text-gray-500 text-sm mb-2">Total Orders</div>
        <div class="text-3xl font-bold text-indigo-600">{{ $totalOrders }}</div>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="text-gray-500 text-sm mb-2">Total Revenue</div>
        <div class="text-3xl font-bold text-green-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="text-gray-500 text-sm mb-2">Completed</div>
        <div class="text-3xl font-bold text-purple-600">{{ $completedOrders }}</div>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="text-gray-500 text-sm mb-2">Pending</div>
        <div class="text-3xl font-bold text-orange-600">{{ $pendingOrders }}</div>
    </div>
</div>

<!-- Orders by Date Chart -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h3 class="text-xl font-bold mb-4">Transaksi Per Hari</h3>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="border-b">
                <tr>
                    <th class="text-left py-3">Tanggal</th>
                    <th class="text-right py-3">Jumlah Order</th>
                    <th class="text-right py-3">Revenue</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ordersByDate as $data)
                    <tr class="border-b">
                        <td class="py-3">{{ \Carbon\Carbon::parse($data->date)->format('d M Y') }}</td>
                        <td class="text-right py-3">{{ $data->count }}</td>
                        <td class="text-right py-3">Rp {{ number_format($data->revenue, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Top Products -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h3 class="text-xl font-bold mb-4">Top 10 Produk Terlaris</h3>
    <table class="w-full">
        <thead class="border-b">
            <tr>
                <th class="text-left py-3">Produk</th>
                <th class="text-right py-3">Terjual</th>
                <th class="text-right py-3">Revenue</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topProducts as $product)
                <tr class="border-b">
                    <td class="py-3">{{ $product->name }}</td>
                    <td class="text-right py-3">{{ $product->total_sold }}</td>
                    <td class="text-right py-3">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Revenue by Payment Method -->
<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-xl font-bold mb-4">Revenue by Payment Method</h3>
    <table class="w-full">
        <thead class="border-b">
            <tr>
                <th class="text-left py-3">Metode Pembayaran</th>
                <th class="text-right py-3">Jumlah</th>
                <th class="text-right py-3">Revenue</th>
            </tr>
        </thead>
        <tbody>
            @foreach($revenueByPayment as $payment)
                <tr class="border-b">
                    <td class="py-3">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                    <td class="text-right py-3">{{ $payment->count }}</td>
                    <td class="text-right py-3">Rp {{ number_format($payment->revenue, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection