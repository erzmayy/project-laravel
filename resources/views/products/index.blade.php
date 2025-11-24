<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Semua Produk</h1>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <form method="GET" action="{{ route('products.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="category" class="w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kondisi</label>
                    <select name="condition" class="w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Semua Kondisi</option>
                        <option value="like_new" {{ request('condition') == 'like_new' ? 'selected' : '' }}>Seperti Baru</option>
                        <option value="good" {{ request('condition') == 'good' ? 'selected' : '' }}>Baik</option>
                        <option value="fair" {{ request('condition') == 'fair' ? 'selected' : '' }}>Cukup</option>
                        <option value="worn" {{ request('condition') == 'worn' ? 'selected' : '' }}>Bekas Pakai</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga Min</label>
                    <input type="number" name="min_price" value="{{ request('min_price') }}" 
                           class="w-full rounded-md border-gray-300 shadow-sm" placeholder="0">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga Max</label>
                    <input type="number" name="max_price" value="{{ request('max_price') }}" 
                           class="w-full rounded-md border-gray-300 shadow-sm" placeholder="1000000">
                </div>

                <div class="md:col-span-4">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
                        Filter Produk
                    </button>
                    <a href="{{ route('products.index') }}" class="ml-2 text-gray-600 hover:text-gray-900">Reset</a>
                </div>
            </form>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($products as $product)
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
                            <h3 class="font-semibold text-gray-900 mb-2 line-clamp-1">{{ $product->name }}</h3>
                            <p class="text-gray-600 text-sm mb-2 line-clamp-2">{{ $product->description }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-xl font-bold text-indigo-600">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <span class="text-sm text-gray-500">{{ $product->category->name }}</span>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500 text-lg">Tidak ada produk ditemukan</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $products->links() }}
        </div>
    </div>
</x-app-layout>