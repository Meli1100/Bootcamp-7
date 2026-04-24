<x-app-layout :title="'Product Category'">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Product Category') }}
        </h2>
    </x-slot>

    <div class="py-12">
        {{-- success and error messages --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-[20px]">
            @include('layouts.success-error-msg')
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Tombol Tambah Kategori -->
                    <div class="mb-6 flex justify-between items-center">
                        <x-primary-button
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'create-new-category')"
                            >{{ __('Tambah Kategori') }}</x-primary-button>
                    </div>

                    <!-- DataTable -->
                    <div class="overflow-x-auto">
                        <table id="productCategoryTable" class="min-w-full table table-striped table-hover">
                            <thead class="bg-gray-100 border-b">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">No</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Nama Kategori</th>
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
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $category->slug }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $category->products_count }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $category->total_stock ?? 'produk belum tersedia' }}</td>
                                        <td class="px-6 py-4 text-sm space-x-2 flex">
                                            <div class="flex items-center gap-2">
                                                <x-primary-button
                                                    x-data=""
                                                    x-on:click.prevent="$dispatch('open-modal', 'edit-category-{{ $category->id }}')"
                                                >
                                                    {{ __('Edit') }}
                                                </x-primary-button>
                                            <button onclick="deleteCategory({{ $category->id }})" 
                                                    class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition text-xs font-semibold">
                                                Hapus
                                            </button>
                                        </td>
                                        @push('scripts')
                                        <x-modal name="edit-category-{{ $category->id }}" maxWidth="md" focusable>
                                            <form method="POST" action="{{ route('admin.product-categories.update', $category) }}" class="p-6 space-y-4">
                                                @csrf
                                                @method('PUT')

                                                <h2 class="text-lg font-medium text-gray-900">Edit Kategori</h2>

                                                <div>
                                                    <x-input-label for="name-{{ $category->id }}" value="Nama Kategori" />
                                                    <x-text-input 
                                                        id="name-{{ $category->id }}" 
                                                        name="name" 
                                                        type="text" 
                                                        class="mt-1 block w-full" 
                                                        value="{{ old('name', $category->name) }}" 
                                                        required 
                                                    />
                                                </div>

                                                <div>
                                                    <x-input-label for="slug-{{ $category->id }}" value="Slug" />
                                                    <x-text-input 
                                                        id="slug-{{ $category->id }}" 
                                                        name="slug" 
                                                        type="text" 
                                                        class="mt-1 block w-full" 
                                                        value="{{ old('slug', $category->slug) }}" 
                                                        required 
                                                    />
                                                </div>

                                                <div class="flex justify-end gap-2 pt-4">
                                                    <x-secondary-button x-on:click="$dispatch('close')">
                                                        Batal
                                                    </x-secondary-button>

                                                    <x-primary-button type="submit">
                                                        Simpan
                                                    </x-primary-button>
                                                </div>
                                            </form>
                                        </x-modal>
                                        @endpush
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
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    @endpush    
    @push('scripts')
    <x-modal name="create-new-category" maxWidth="md" focusable>
        <form method="POST" action="{{ route('admin.product-categories.store') }}" class="p-6">
            @csrf
            <h2 class="text-lg font-medium text-gray-900 mb-4">Tambah Kategori</h2>

            <div class="mt-4">
                <x-input-label for="name" value="{{ __('Nama Kategori') }}" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Batal') }}
                </x-secondary-button>

                <x-primary-button class="ms-3" type="submit">
                    {{ __('Simpan') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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

        function deleteCategory(categoryId) {
            if (confirm('Apakah Anda yakin ingin menghapus kategori ini?')) {
                fetch(`/admin/product-categories/${categoryId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
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
    @endpush

</x-app-layout>
