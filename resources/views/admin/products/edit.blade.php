@extends('layouts.admin')

@section('title', 'Edit Produk')

@section('content')
<div class="max-w-3xl">
    <h2 class="text-2xl font-bold mb-6">Edit Produk</h2>

    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="bg-white rounded-lg shadow-md p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Produk</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full rounded-md border-gray-300 shadow-sm" required>
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                <select name="category_id" class="w-full rounded-md border-gray-300 shadow-sm" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                <input type="number" name="price" value="{{ old('price', $product->price) }}" class="w-full rounded-md border-gray-300 shadow-sm" required>
                @error('price')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Stok</label>
                <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="w-full rounded-md border-gray-300 shadow-sm" required>
                @error('stock')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kondisi</label>
                <select name="condition" class="w-full rounded-md border-gray-300 shadow-sm" required>
                    <option value="like_new" {{ old('condition', $product->condition) == 'like_new' ? 'selected' : '' }}>Seperti Baru</option>
                    <option value="good" {{ old('condition', $product->condition) == 'good' ? 'selected' : '' }}>Baik</option>
<option value="fair" {{ old('condition', $product->condition) == 'fair' ? 'selected' : '' }}>Cukup</option>
                    <option value="worn" {{ old('condition', $product->condition) == 'worn' ? 'selected' : '' }}>Bekas Pakai</option>
                </select>
                @error('condition')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full rounded-md border-gray-300 shadow-sm" required>
                    <option value="available" {{ old('status', $product->status) == 'available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="sold" {{ old('status', $product->status) == 'sold' ? 'selected' : '' }}>Terjual</option>
                    <option value="reserved" {{ old('status', $product->status) == 'reserved' ? 'selected' : '' }}>Reserved</option>
                </select>
                @error('status')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Brand (Opsional)</label>
                <input type="text" name="brand" value="{{ old('brand', $product->brand) }}" class="w-full rounded-md border-gray-300 shadow-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Size (Opsional)</label>
                <input type="text" name="size" value="{{ old('size', $product->size) }}" class="w-full rounded-md border-gray-300 shadow-sm">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="description" rows="4" class="w-full rounded-md border-gray-300 shadow-sm" required>{{ old('description', $product->description) }}</textarea>
            </div>

            @if($product->images && count($product->images) > 0)
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Saat Ini</label>
                    <div class="grid grid-cols-5 gap-2">
                        @foreach($product->images as $image)
                            <img src="{{ Storage::url($image) }}" alt="Product" class="w-full h-24 object-cover rounded">
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tambah Foto Baru</label>
                <input type="file" name="images[]" multiple accept="image/*" class="w-full">
                <p class="text-sm text-gray-500 mt-1">Upload foto baru akan ditambahkan ke foto yang sudah ada</p>
            </div>
        </div>

        <div class="flex space-x-2 mt-6">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
                Update Produk
            </button>
            <a href="{{ route('admin.products.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection