<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Product Category') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Tombol Tambah Kategori -->
                    <div class="mb-6 flex justify-between items-center">
                        <h3 class="text-lg font-semibold">Daftar Kategori Produk</h3>
                        <a href="{{ route('admin.product-categories.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            + Tambah Kategori
                        </a>
                    </div>

                    <!-- DataTable -->
                    <div class="overflow-x-auto">
                        <table id="productCategoryTable" class="min-w-full table table-striped table-hover">
                            <thead class="bg-gray-100 border-b">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">No</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Nama Kategori</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Deskripsi</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Slug</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Jumlah Produk</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Total Stok</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productCategories as $category)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $category->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($category->description, 50) }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $category->slug }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $category->products_count }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $category->total_stock }}</td>
                                        <td class="px-6 py-4 text-sm space-x-2 flex">
                                            <a href="{{ route('admin.product-categories.edit', $category->id) }}" 
                                               class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition text-xs font-semibold">
                                                Edit
                                            </a>
                                            <button onclick="deleteCategory({{ $category->id }})" 
                                                    class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition text-xs font-semibold">
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($productCategories->isEmpty())
                        <div class="text-center py-8 text-gray-500">
                            <p>Tidak ada kategori produk. <a href="{{ route('admin.product-categories.create') }}" class="text-blue-600 hover:underline">Tambah kategori sekarang</a></p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @push('styles')       
        <!-- DataTable CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    @endpush    
    @push('scripts')
        <!-- DataTable JS -->
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    @endpush

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
    </script>
</x-app-layout>
