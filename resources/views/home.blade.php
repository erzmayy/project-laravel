<x-app-layout>
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-4">Temukan Thrift Favoritmu</h1>
                <p class="text-xl md:text-2xl mb-8">Barang bekas berkualitas dengan harga terjangkau</p>
                <a href="{{ route('products.index') }}" class="bg-white text-indigo-600 px-8 py-3 rounded-lg text-lg font-semibold hover:bg-gray-100 inline-block">
                    Mulai Belanja
                </a>
            </div>
        </div>
    </div>

    <!-- Categories -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Kategori Populer</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($categories as $category)
                <a href="{{ route('products.index', ['category' => $category->slug]) }}" 
                   class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-xl transition">
                    <div class="text-4xl mb-2">👕</div>
                    <h3 class="font-semibold text-gray-900">{{ $category->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $category->products_count }} produk</p>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Featured Products -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Produk Terbaru</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredProducts as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                    <a href="{{ route('products.show', $product->slug) }}">
                        <div class="h-64 bg-gray-200 flex items-center justify-center">
                            @if($product->images && count($product->images) > 0)
                                <img src="{{ Storage::url($product->images[0]) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-gray-400 text-6xl">📦</span>
                            @endif
                        </div>
                        <div class="p-4">
                            <span class="inline-block bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded mb-2">
                                {{ ucfirst(str_replace('_', ' ', $product->condition)) }}
                            </span>
                            <h3 class="font-semibold text-gray-900 mb-2">{{ $product->name }}</h3>
                            <p class="text-gray-600 text-sm mb-2 line-clamp-2">{{ $product->description }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-xl font-bold text-indigo-600">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <span class="text-sm text-gray-500">{{ $product->category->name }}</span>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-8">
            <a href="{{ route('products.index') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">
                Lihat Semua Produk →
            </a>
        </div>
    </div>
</x-app-layout>