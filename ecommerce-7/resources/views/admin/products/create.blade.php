<x-app-layout :title="'Create Product'">
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create Product') }}
            </h2>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div>
                            <x-input-label for="name" :value="'Product Name'" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" class="mt-1 block w-full"></textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="image_input" :value="__('Product Image')" />
                            <x-text-input id="image_input" name="image_input" type="file" class="mt-1 block w-full" required accept="image/*" />
                            <input type="hidden" id="image" name="image" />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>
                        <div id="crop-container" class="mt-4 hidden">
                            <p class="font-medium mb-2">Crop Image (1:1, 800x800)</p>
                            <div id="croppie-demo" class="w-full"></div>
                        </div>
                        <div>
                            <x-input-label for="price" :value="__('Price')" />
                            <x-text-input id="price" name="price" type="number" step="1" min="0" class="mt-1 block w-full" required />
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="stock" :value="__('Stock')" />
                            <x-text-input id="stock" name="stock" type="number" min="0" class="mt-1 block w-full" required />
                            <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="product_category_id" :value="__('Category')" />
                            <select id="product_category_id" name="product_category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="">Select Category</option>
                                @foreach ($productCategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('product_category_id')" class="mt-2" />
                        </div>
                        <div>
                            <x-primary-button type="submit">
                                {{ __('Create Product') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css" />
    @endpush

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const imageInput = document.getElementById('image_input');
                const croppieContainer = document.getElementById('crop-container');
                const croppieDemo = document.getElementById('croppie-demo');
                const croppedImageInput = document.getElementById('image');
                const form = document.querySelector('form');
                let croppieInstance = null;
                let isSubmitting = false;

                function initCroppie(src) {
                    if (croppieInstance) {
                        croppieInstance.destroy();
                    }

                    croppieInstance = new Croppie(croppieDemo, {
                        viewport: { width: 500, height: 500, type: 'square' },
                        boundary: { width: 520, height: 520 },
                        enableExif: true,
                        enableOrientation: true,
                        enableZoom: true,
                        minZoom: 0,
                        maxZoom: 2
                    });

                    croppieInstance.bind({ url: src });
                    croppieContainer.classList.remove('hidden');
                }

                imageInput.addEventListener('change', function () {
                    const file = this.files[0];
                    if (!file) {
                        return;
                    }

                    const validImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg', 'image/webp'];
                    if (!validImageTypes.includes(file.type)) {
                        alert('Please select a valid image file (JPEG, PNG, GIF, JPG, WEBP).');
                        this.value = '';
                        return;
                    }

                    const maxSize = 2 * 1024 * 1024;
                    if (file.size > maxSize) {
                        alert('File size must be less than 2MB.');
                        this.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function (e) {
                        initCroppie(e.target.result);
                    };
                    reader.readAsDataURL(file);
                });

                form.addEventListener('submit', function (event) {
                    if (isSubmitting) {
                        return;
                    }

                    const priceValue = parseFloat(document.getElementById('price').value);
                    const stockValue = parseInt(document.getElementById('stock').value, 10);
                    const nameValue = document.getElementById('name').value.trim();
                    const descriptionValue = document.getElementById('description').value.trim();

                    if (!nameValue) {
                        alert('Please enter a product name.');
                        event.preventDefault();
                        return;
                    }

                    if (!descriptionValue) {
                        alert('Please enter a product description.');
                        event.preventDefault();
                        return;
                    }

                    if (priceValue < 0) {
                        alert('Price must be a positive number.');
                        event.preventDefault();
                        return;
                    }

                    if (stockValue < 0) {
                        alert('Stock must be a positive number.');
                        event.preventDefault();
                        return;
                    }

                    if (croppieInstance) {
                        event.preventDefault();
                        croppieInstance.result({
                            type: 'base64',
                            size: { width: 800, height: 800 },
                            format: 'png',
                            quality: 1
                        }).then(function (base64) {
                            croppedImageInput.value = base64;
                            isSubmitting = true;
                            form.submit();
                        });
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>