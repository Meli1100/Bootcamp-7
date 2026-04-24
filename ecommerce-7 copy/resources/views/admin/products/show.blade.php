<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    <div>
                        <span class="font-semibold">Nama:</span>
                        {{ $product->name }}
                    </div>
                    <div>
                        <span class="font-semibold">Deskripsi:</span>
                        {{ $product->description }}
                    </div>
                    <div>
                        <span class="font-semibold">Harga:</span>
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </div>
                    <div>
                        <span class="font-semibold">Stok:</span>
                        {{ $product->stock }}
                    </div>
                    <div>
                        <span class="font-semibold">Kategori:</span>
                        {{ $product->product_category ? $product->product_category->name : 'Uncategorized' }}
                    </div>
                    <div>
                        <span class="font-semibold">Gambar:</span><br>
                        <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}" class="w-48 h-48 object-cover rounded mt-2">
                    </div>
                    <div class="flex gap-2 mt-4">
                        <a href="{{ route('admin.products.edit', $product->id) }}"
                           class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            Edit
                        </a>
                        <a href="{{ route('admin.products.index') }}"
                           class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>