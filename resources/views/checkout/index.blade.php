<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>

        <form method="POST" action="{{ route('checkout.store') }}">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    <!-- Shipping Info -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Informasi Pengiriman</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Penerima</label>
                                <input type="text" name="shipping_name" value="{{ old('shipping_name', auth()->user()->name) }}" 
                                       class="w-full rounded-md border-gray-300 shadow-sm" required>
                                @error('shipping_name')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                                <input type="text" name="shipping_phone" value="{{ old('shipping_phone', auth()->user()->phone) }}" 
                                       class="w-full rounded-md border-gray-300 shadow-sm" required>
                                @error('shipping_phone')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap</label>
                                <textarea name="shipping_address" rows="3" class="w-full rounded-md border-gray-300 shadow-sm" required>{{ old('shipping_address', auth()->user()->address) }}</textarea>
                                @error('shipping_address')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Metode Pembayaran</h2>
                        <div class="space-y-3">
                            <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="bank_transfer" class="mr-3" required>
                                <div>
                                    <p class="font-semibold">Transfer Bank</p>
                                    <p class="text-sm text-gray-600">BCA, Mandiri, BNI</p>
                                </div>
                            </label>
                            <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="cod" class="mr-3">
                                <div>
                                    <p class="font-semibold">COD (Cash on Delivery)</p>
                                    <p class="text-sm text-gray-600">Bayar saat barang diterima</p>
                                </div>
                            </label>
                        </div>
                        @error('payment_method')
                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Catatan (Opsional)</h2>
                        <textarea name="notes" rows="3" class="w-full rounded-md border-gray-300 shadow-sm" placeholder="Tambahkan catatan untuk penjual...">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Order Summary -->
                <div>
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Ringkasan Pesanan</h2>
                        <div class="space-y-3 mb-4">
                            @foreach($cartItems as $item)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">{{ $item->product->name }} (x{{ $item->quantity }})</span>
                                    <span class="font-semibold">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="border-t pt-4 space-y-2 mb-6">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-semibold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Ongkir</span>
                                <span class="font-semibold">Gratis</span>
                            </div>
                            <div class="flex justify-between text-lg font-bold pt-2">
                                <span>Total</span>
                                <span class="text-indigo-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <button type="submit" class="block w-full bg-indigo-600 text-white text-center py-3 rounded-lg text-lg font-semibold hover:bg-indigo-700">
                            Buat Pesanan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>