@extends('layouts.admin')

@section('title', 'Konfirmasi Pembayaran')

@section('content')
<h2 class="text-2xl font-bold mb-6">Konfirmasi Pembayaran</h2>

<!-- Filter Tabs -->
<div class="bg-white rounded-lg shadow-md mb-6">
    <div class="border-b">
        <nav class="flex space-x-4 px-6">
            <a href="{{ route('admin.payments.index', ['status' => 'pending']) }}" 
               class="py-4 px-3 border-b-2 font-medium text-sm {{ $status == 'pending' ? 'border-yellow-500 text-yellow-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                Pending
            </a>
            <a href="{{ route('admin.payments.index', ['status' => 'approved']) }}" 
               class="py-4 px-3 border-b-2 font-medium text-sm {{ $status == 'approved' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                Approved
            </a>
            <a href="{{ route('admin.payments.index', ['status' => 'rejected']) }}" 
               class="py-4 px-3 border-b-2 font-medium text-sm {{ $status == 'rejected' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                Rejected
            </a>
        </nav>
    </div>
</div>

<!-- Confirmations Table -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Order</th>
                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Customer</th>
                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Bank</th>
                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Amount</th>
                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Upload Date</th>
                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($confirmations as $confirmation)
                <tr>
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-medium text-gray-900">{{ $confirmation->order->order_number }}</p>
                            <p class="text-sm text-gray-500">Total: Rp {{ number_format($confirmation->order->total_amount, 0, ',', '.') }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-900">{{ $confirmation->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $confirmation->user->email }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-medium">{{ $confirmation->bank_name }}</p>
                        <p class="text-sm text-gray-500">{{ $confirmation->account_name }}</p>
                    </td>
                    <td class="px-6 py-4 font-semibold">
                        Rp {{ number_format($confirmation->amount, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $confirmation->confirmed_at->format('d M Y H:i') }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full
                            {{ $confirmation->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $confirmation->status == 'approved' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $confirmation->status == 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($confirmation->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.payments.show', $confirmation) }}" class="text-indigo-600 hover:text-indigo-900">
                            Detail
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        Tidak ada konfirmasi pembayaran dengan status {{ $status }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-4">
    {{ $confirmations->links() }}
</div>
@endsection