@extends('layouts.admin')

@section('title', 'Tambah Produk')

@section('content')
<div class="max-w-3xl">
    <h2 class="text-2xl font-bold mb-6">Tambah Produk Baru</h2>

    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="bg-white rounded-lg shadow-md p-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Produk</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-md border-gray-300 shadow-sm" required>
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                <select name="category_id" class="w-full rounded-md border-gray-300 shadow-sm" required>
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                <input type="number" name="price" value="{{ old('price') }}" class="w-full rounded-md border-gray-300 shadow-sm" required>
                @error('price')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Stok</label>
                <input type="number" name="stock" value="{{ old('stock', 1) }}" class="w-full rounded-md border-gray-300 shadow-sm" required>
                @error('stock')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kondisi</label>
                <select name="condition" class="w-full rounded-md border-gray-300 shadow-sm" required>
                    <option value="like_new" {{ old('condition') == 'like_new' ? 'selected' : '' }}>Seperti Baru</option>
                    <option value="good" {{ old('condition') == 'good' ? 'selected' : '' }}>Baik</option>
                    <option value="fair" {{ old('condition') == 'fair' ? 'selected' : '' }}>Cukup</option>
                    <option value="worn" {{ old('condition') == 'worn' ? 'selected' : '' }}>Bekas Pakai</option>
                </select>
                @error('condition')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Brand (Opsional)</label>
                <input type="text" name="brand" value="{{ old('brand') }}" class="w-full rounded-md border-gray-300 shadow-sm">
                @error('brand')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Size (Opsional)</label>
                <input type="text" name="size" value="{{ old('size') }}" class="w-full rounded-md border-gray-300 shadow-sm">
                @error('size')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="description" rows="4" class="w-full rounded-md border-gray-300 shadow-sm" required>{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Foto Produk (Max 5 foto)</label>
                <input type="file" name="images[]" multiple accept="image/*" class="w-full">
                <p class="text-sm text-gray-500 mt-1">Upload maksimal 5 foto (JPG, PNG, max 2MB per file)</p>
                @error('images.*')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex space-x-2 mt-6">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
                Simpan Produk
            </button>
            <a href="{{ route('admin.products.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection