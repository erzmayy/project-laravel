<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <a href="{{ route('orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-800">&larr; Kembali ke Detail Order</a>
        </div>

        <h1 class="text-3xl font-bold text-gray-900 mb-2">Konfirmasi Pembayaran</h1>
        <p class="text-gray-600 mb-8">Order #{{ $order->order_number }}</p>

        <!-- Order Info -->
        <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-6 mb-8">
            <h3 class="font-bold text-indigo-900 mb-2">Total yang Harus Dibayar</h3>
            <p class="text-3xl font-bold text-indigo-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
            
            <div class="mt-4 pt-4 border-t border-indigo-200">
                <h4 class="font-semibold text-indigo-900 mb-2">Transfer ke Rekening:</h4>
                <div class="space-y-1 text-sm text-indigo-800">
                    <p><strong>BCA:</strong> 1234567890 a/n ThriftShop</p>
                    <p><strong>Mandiri:</strong> 9876543210 a/n ThriftShop</p>
                    <p><strong>BNI:</strong> 5555666677 a/n ThriftShop</p>
                </div>
            </div>
        </div>

        <!-- Upload Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Upload Bukti Transfer</h2>

            <form method="POST" action="{{ route('payment.store', $order) }}" enctype="multipart/form-data">
                @csrf

                <div class="space-y-4">
                    <!-- Bank Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bank Tujuan Transfer <span class="text-red-600">*</span></label>
                        <select name="bank_name" class="w-full rounded-md border-gray-300 shadow-sm" required>
                            <option value="">Pilih Bank</option>
                            <option value="BCA">BCA</option>
                            <option value="Mandiri">Mandiri</option>
                            <option value="BNI">BNI</option>
                        </select>
                        @error('bank_name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Account Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pengirim <span class="text-red-600">*</span></label>
                        <input type="text" name="account_name" value="{{ old('account_name') }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm" 
                               placeholder="Nama sesuai rekening" required>
                        @error('account_name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Account Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Rekening Pengirim <span class="text-red-600">*</span></label>
                        <input type="text" name="account_number" value="{{ old('account_number') }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm" 
                               placeholder="Nomor rekening Anda" required>
                        @error('account_number')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Amount -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Transfer <span class="text-red-600">*</span></label>
                        <input type="number" name="amount" value="{{ old('amount', $order->total_amount) }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm" 
                               placeholder="Jumlah yang ditransfer" required>
                        @error('amount')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Proof Image -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Transfer (Foto/Screenshot) <span class="text-red-600">*</span></label>
                        <input type="file" name="proof_image" accept="image/*" class="w-full" required>
                        <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG (Max 2MB)</p>
                        @error('proof_image')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                        <textarea name="notes" rows="3" class="w-full rounded-md border-gray-300 shadow-sm" placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg text-lg font-semibold hover:bg-indigo-700">
                        Upload Bukti Transfer
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>