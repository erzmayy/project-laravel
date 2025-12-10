<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg mb-6">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-semibold">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <!-- Order Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detail Pesanan</h1>
                    <p class="text-gray-500 mt-1">{{ $order->order_number }}</p>
                </div>
                <span class="px-4 py-2 rounded-full text-sm font-semibold
                    {{ $order->status == 'completed' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $order->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                    {{ in_array($order->status, ['paid', 'processing', 'shipped']) ? 'bg-blue-100 text-blue-800' : '' }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6 pt-6 border-t">
                <div>
                    <p class="text-sm text-gray-500">Tanggal Pesanan</p>
                    <p class="font-semibold">{{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Metode Pembayaran</p>
                    <p class="font-semibold">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Pembayaran</p>
                    <p class="font-bold text-lg text-indigo-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Produk yang Dipesan</h2>
            <div class="space-y-4">
                @foreach($order->items as $item)
                    <div class="flex items-center space-x-4 pb-4 border-b last:border-b-0">
                        <div class="w-20 h-20 bg-gray-200 rounded flex-shrink-0">
                            @if($item->product->images && count($item->product->images) > 0)
                                <img src="{{ Storage::url($item->product->images[0]) }}" alt="{{ $item->product->name }}" class="w-20 h-20 object-cover rounded">
                            @else
                                <div class="w-20 h-20 flex items-center justify-center text-gray-400 text-3xl">📦</div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ $item->product->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $item->product->category->name }}</p>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-gray-900">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 pt-6 border-t">
                <div class="flex justify-between items-center text-lg">
                    <span class="font-bold">Total</span>
                    <span class="font-bold text-indigo-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Shipping Info -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Informasi Pengiriman</h2>
            <div class="space-y-2">
                <div>
                    <p class="text-sm text-gray-500">Nama Penerima</p>
                    <p class="font-semibold">{{ $order->shipping_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Nomor Telepon</p>
                    <p class="font-semibold">{{ $order->shipping_phone }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Alamat Lengkap</p>
                    <p class="font-semibold">{{ $order->shipping_address }}</p>
                </div>
                @if($order->notes)
                    <div class="mt-4 p-3 bg-gray-50 rounded">
                        <p class="text-sm text-gray-500">Catatan</p>
                        <p class="text-gray-700">{{ $order->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Payment Instructions -->
        @if($order->status == 'pending')
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
                <h3 class="font-bold text-yellow-800 mb-2">Instruksi Pembayaran</h3>
                <p class="text-sm text-yellow-700 mb-3">Silakan lakukan pembayaran untuk melanjutkan pesanan Anda.</p>
                @if($order->payment_method == 'bank_transfer')
                    <div class="bg-white p-4 rounded">
                        <p class="font-semibold mb-2">Transfer Bank:</p>
                        <ul class="text-sm space-y-1 text-gray-700">
                            <li>BCA: 1234567890 a/n ThriftShop</li>
                            <li>Mandiri: 9876543210 a/n ThriftShop</li>
                            <li>BNI: 5555666677 a/n ThriftShop</li>
                        </ul>
                        <p class="text-sm text-gray-600 mt-3">Jumlah yang harus dibayar: <span class="font-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span></p>
                    </div>
                @elseif($order->payment_method == 'cod')
                    <p class="text-sm text-gray-700">Anda memilih pembayaran COD. Siapkan uang tunai saat barang tiba.</p>
                @endif
            </div>
        @endif
                <!-- Payment Confirmation Button - TAMBAHKAN INI -->
        @if($order->status == 'pending' && $order->payment_method == 'bank_transfer')
            @if(!$order->paymentConfirmation)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
                    <h3 class="font-bold text-yellow-900 mb-2">Sudah Transfer?</h3>
                    <p class="text-sm text-yellow-800 mb-4">Upload bukti transfer untuk mempercepat verifikasi pembayaran</p>
                    <a href="{{ route('payment.create', $order) }}" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 font-semibold">
                        📤 Upload Bukti Transfer
                    </a>
                </div>
            @else
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                    <h3 class="font-bold text-blue-900 mb-2">Bukti Transfer Sudah Diupload</h3>
                    <p class="text-sm text-blue-800 mb-2">Status: 
                        <span class="font-semibold">
                            @if($order->paymentConfirmation->status == 'pending') Menunggu Verifikasi Admin @endif
                            @if($order->paymentConfirmation->status == 'approved') ✓ Disetujui @endif
                            @if($order->paymentConfirmation->status == 'rejected') ✗ Ditolak @endif
                        </span>
                    </p>
                    <a href="{{ route('payment.show', $order) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                        Lihat Detail Pembayaran →
                    </a>
                </div>
            @endif
        @endif

        <!-- Action Buttons -->
        <div class="flex space-x-3">
            <a href="{{ route('orders.index') }}" class="flex-1 bg-gray-200 text-gray-700 text-center py-3 rounded-lg hover:bg-gray-300 font-semibold">
                Lihat Semua Pesanan
            </a>
            <a href="{{ route('home') }}" class="flex-1 bg-indigo-600 text-white text-center py-3 rounded-lg hover:bg-indigo-700 font-semibold">
                Kembali Belanja
            </a>
        </div>
    </div>
</x-app-layout>