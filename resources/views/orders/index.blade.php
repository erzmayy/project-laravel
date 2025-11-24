<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Pesanan Saya</h1>

        @if($orders->isEmpty())
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <div class="text-6xl mb-4">📦</div>
                <h2 class="text-2xl font-semibold text-gray-900 mb-2">Belum Ada Pesanan</h2>
                <p class="text-gray-600 mb-6">Anda belum pernah melakukan pemesanan</p>
                <a href="{{ route('products.index') }}" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">
                    Mulai Belanja
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($orders as $order)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex flex-col md:flex-row md:justify-between md:items-start mb-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">{{ $order->order_number }}</h3>
                                <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <div class="mt-2 md:mt-0">
                                <span class="px-3 py-1 text-sm rounded-full
                                    {{ $order->status == 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $order->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ in_array($order->status, ['paid', 'processing', 'shipped']) ? 'bg-blue-100 text-blue-800' : '' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>

                        <div class="border-t pt-4">
                            <div class="space-y-2">
                                @foreach($order->items->take(3) as $item)
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 bg-gray-200 rounded flex-shrink-0">
                                            @if($item->product->images && count($item->product->images) > 0)
                                                <img src="{{ Storage::url($item->product->images[0]) }}" alt="{{ $item->product->name }}" class="w-12 h-12 object-cover rounded">
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-900">{{ $item->product->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $item->quantity }}x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                                @if($order->items->count() > 3)
                                    <p class="text-sm text-gray-500 pl-15">+{{ $order->items->count() - 3 }} produk lainnya</p>
                                @endif
                            </div>
                        </div>

                        <div class="border-t mt-4 pt-4 flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-500">Total Pembayaran</p>
                                <p class="text-xl font-bold text-indigo-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            </div>
                            <a href="{{ route('orders.show', $order) }}" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</x-app-layout>