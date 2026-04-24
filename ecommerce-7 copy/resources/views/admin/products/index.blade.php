<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-[20px]">
            @include('layouts.success-error-msg')
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6 flex justify-between items-center">
                        <h3 class="text-lg font-semibold">Daftar Produk</h3>
                        <a href="{{ route('admin.products.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                            + Tambah Produk
                        </a>
                    </div>

                    <!-- DataTable -->
                    <div class="mb-4">
                        <form method="GET" action="{{ route('admin.products.index') }}" class="flex items-center gap-2">
                            <input type="text" name="search" placeholder="Cari produk..." value="{{ request('search') }}" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button type="submit" class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-blue-500">
                                Cari
                            </button>
                        </form>
                    </div>
                    <div class="overflow-x-auto">
                        <table id="productCategoryTable" class="min-w-full table table-striped table-hover">
                            <thead class="bg-gray-100 border-b">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">ID</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Nama</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Slug</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Clicks</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Deskripsi</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Harga</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Stok</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Gambar</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Kategori</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $product->id }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $product->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $product->slug }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($product->description, 50) }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $product->stock }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $product->clicks }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}" class="w-16 h-16 object-cover rounded">
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $product->product_category ? $product->product_category->name : 'Uncategorized' }}</td>
                                        <td class="px-6 py-4 text-sm space-x-2 flex">
                                            <a href="{{ route('admin.products.show', $product->id) }}"
                                                class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 transition text-xs font-semibold">
                                                    Lihat
                                            </a>
                                            <a href="{{ route('admin.products.edit', $product->id) }}"
                                               class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition text-xs font-semibold">
                                                Edit
                                            </a>
                                            <button onclick="deleteProduct({{ $product->id }})" 
                                                class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition text-xs font-semibold">
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    <!-- Empty line for spacing -->
                    </div>                   
                    @if($products->isEmpty())
                        <div class="text-center py-8 text-gray-500">
                            <p>Tidak ada produk. <a href="{{ route('admin.products.create') }}" class="text-blue-600 hover:underline">Tambah produk sekarang</a></p>
                        </div>
                    @endif
                </div>
                <div class="p-6">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        // Inisialisasi DataTable
        document.addEventListener('DOMContentLoaded', function() {
            $('#productCategoryTable').DataTable({
                responsive: true,
                pageLength: 10,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                },
                columnDefs: [
                    {
                        targets: 3,
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });

        // Fungsi hapus kategori
        function deleteCategory(categoryId) {
            if (confirm('Apakah Anda yakin ingin menghapus kategori ini?')) {
                fetch(`/admin/product-categories/${categoryId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Kategori berhasil dihapus');
                        location.reload();
                    } else {
                        alert('Gagal menghapus kategori: ' + (data.message || 'Error tidak diketahui'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus kategori');
                });
            }
        }

        // Fungsi hapus produk
        function deleteProduct(productId) {
            if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
                fetch(`/admin/products/${productId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Produk berhasil dihapus');
                        location.reload();
                    } else {
                        alert('Gagal menghapus produk: ' + (data.message || 'Error tidak diketahui'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus produk');
                });
            }
        }
    </script>
</x-app-layout>
