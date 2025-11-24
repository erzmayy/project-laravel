<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Product Images -->
            <div>
                <div class="bg-gray-200 rounded-lg h-96 flex items-center justify-center mb-4">
                    @if($product->images && count($product->images) > 0)
                        <img src="{{ Storage::url($product->images[0]) }}" alt="{{ $product->name }}" class="w-full h-full object-cover rounded-lg">
                    @else
                        <span class="text-gray-400 text-9xl">📦</span>
                    @endif
                </div>
                @if($product->images && count($product->images) > 1)
                    <div class="grid grid-cols-4 gap-2">
                        @foreach(array_slice($product->images, 1, 4) as $image)
                            <img src="{{ Storage::url($image) }}" alt="{{ $product->name }}" class="w-full h-24 object-cover rounded-lg">
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Product Info -->
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>
                
                <div class="flex items-center space-x-4 mb-4">
                    <span class="inline-block bg-indigo-100 text-indigo-800 px-3 py-1 rounded-lg">
                        {{ ucfirst(str_replace('_', ' ', $product->condition)) }}
                    </span>
                    <span class="text-gray-500">{{ $product->category->name }}</span>
                </div>

                <div class="text-4xl font-bold text-indigo-600 mb-6">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </div>

                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold mb-2">Detail Produk:</h3>
                    <ul class="space-y-1 text-gray-600">
                        @if($product->brand)
                            <li><span class="font-medium">Brand:</span> {{ $product->brand }}</li>
                        @endif
                        @if($product->size)
                            <li><span class="font-medium">Size:</span> {{ $product->size }}</li>
                        @endif
                        <li><span class="font-medium">Stok:</span> {{ $product->stock }} pcs</li>
                        <li><span class="font-medium">Penjual:</span> {{ $product->seller->name }}</li>
                    </ul>
                </div>

                <p class="text-gray-700 mb-6">{{ $product->description }}</p>

                @auth
                    @if($product->stock > 0 && $product->status == 'available')
                        <form method="POST" action="{{ route('cart.add', $product) }}">
                            @csrf
                            <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg text-lg font-semibold hover:bg-indigo-700">
                                Tambah ke Keranjang
                            </button>
                        </form>
                    @else
                        <button disabled class="w-full bg-gray-400 text-white py-3 rounded-lg text-lg font-semibold cursor-not-allowed">
                            Stok Habis
                        </button>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="block w-full bg-indigo-600 text-white py-3 rounded-lg text-lg font-semibold hover:bg-indigo-700 text-center">
                        Login untuk Membeli
                    </a>
                @endauth
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
            <div class="mt-16">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Produk Serupa</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $related)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                            <a href="{{ route('products.show', $related->slug) }}">
                                <div class="h-48 bg-gray-200 flex items-center justify-center">
                                    @if($related->images && count($related->images) > 0)
                                        <img src="{{ Storage::url($related->images[0]) }}" alt="{{ $related->name }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-gray-400 text-5xl">📦</span>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900 mb-2">{{ $related->name }}</h3>
                                    <span class="text-lg font-bold text-indigo-600">Rp {{ number_format($related->price, 0, ',', '.') }}</span>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout>