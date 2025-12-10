@extends('layouts.admin')

@section('title', 'Buat Shipping')

@section('content')
<div class="max-w-3xl">
    <h2 class="text-2xl font-bold mb-6">Buat Shipping untuk Order {{ $order->order_number }}</h2>

    <form method="POST" action="{{ route('admin.shipping.store', $order) }}" class="bg-white rounded-lg shadow-md p-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kurir</label>
                <select name="courier" class="w-full rounded-md border-gray-300 shadow-sm" required>
                    <option value="">Pilih Kurir</option>
                    <option value="JNE">JNE</option>
                    <option value="J&T">J&T Express</option>
                    <option value="SiCepat">SiCepat</option>
                    <option value="Anteraja">Anteraja</option>
                    <option value="POS">POS Indonesia</option>
                </select>
                @error('courier')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Service</label>
                <select name="service" class="w-full rounded-md border-gray-300 shadow-sm" required>
                    <option value="">Pilih Service</option>
                    <option value="REG">Regular</option>
                    <option value="YES">YES (1 Day)</option>
                    <option value="OKE">OKE (Economy)</option>
                    <option value="CARGO">Cargo</option>
                </select>
                @error('service')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Ongkos Kirim</label>
                <input type="number" name="cost" value="{{ old('cost', 0) }}" class="w-full rounded-md border-gray-300 shadow-sm" required>
                @error('cost')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Estimasi (Hari)</label>
                <input type="number" name="estimated_days" value="{{ old('estimated_days') }}" class="w-full rounded-md border-gray-300 shadow-sm">
                @error('estimated_days')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Resi (Opsional)</label>
                <input type="text" name="tracking_number" value="{{ old('tracking_number') }}" class="w-full rounded-md border-gray-300 shadow-sm" placeholder="Masukkan nomor resi">
                @error('tracking_number')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex space-x-2 mt-6">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
                Buat Shipping
            </button>
            <a href="{{ route('admin.orders.show', $order) }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection