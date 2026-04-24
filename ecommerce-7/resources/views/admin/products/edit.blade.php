<x-app-layout :title="'Edit Product'">
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Product') }}
            </h2>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')
                        <div>
                            <x-input-label for="name" :value="'Product Name'" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                value="{{ old('name', $product->name) }}" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" class="mt-1 block w-full">{{ old('description', $product->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="image_input" :value="__('Product Image')" />
                            @if ($product->image)
                                <div class="mb-3">
                                    <p class="text-sm text-gray-500">Current image:</p>
                                    <img src="{{ asset('images/' . $product->image) }}" alt="Current product image"
                                        class="w-32 h-32 object-cover rounded-md border" />
                                </div>
                            @endif
                            <input id="image_input" name="image_file" type="file"
                                class="mt-1 block w-full" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" />
                            <p class="text-sm text-gray-500 mt-1">Leave empty to keep the current image.</p>
                            <input id="cropped_image" name="cropped_image" type="hidden" value="{{ old('cropped_image') }}" />
                            <x-input-error :messages="$errors->get('cropped_image')" class="mt-2" />
                            <x-input-error :messages="$errors->get('image_file')" class="mt-2" />
                        </div>
                        <div id="crop-container" class="mt-4 hidden">
                            <p class="text-sm text-gray-600 mb-2">Crop your image before submitting:</p>
                            <div id="croppie-demo" class="w-full max-w-md mx-auto"></div>
                        </div>
                        <div>
                            <x-input-label for="price" :value="__('Price')" />
                            <x-text-input id="price" name="price" type="number" step="1" min="0"
                                class="mt-1 block w-full" value="{{ old('price', $product->price) }}" required />
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="stock" :value="__('Stock')" />
                            <x-text-input id="stock" name="stock" type="number" min="0"
                                class="mt-1 block w-full" value="{{ old('stock', $product->stock) }}" required />
                            <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="product_category_id" :value="__('Category')" />
                            <select id="product_category_id" name="product_category_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required>
                                <option value="">Select Category</option>
                                @foreach ($productCategories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('product_category_id', $product->product_category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('product_category_id')" class="mt-2" />
                        </div>
                        <div>
                            <x-primary-button type="submit">
                                {{ __('Update Product') }}
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
                const croppedImageInput = document.getElementById('cropped_image');
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

                    const existingImage = document.querySelector('img[alt="Current product image"]');

                    if (!croppieInstance && !croppedImageInput.value && !existingImage) {
                        alert('Please select and crop an image first.');
                        event.preventDefault();
                        return;
                    }

                    if (priceValue < 0 || Number.isNaN(priceValue)) {
                        alert('Price must be a valid positive number.');
                        event.preventDefault();
                        return;
                    }

                    if (stockValue < 0 || Number.isNaN(stockValue)) {
                        alert('Stock must be a valid positive number.');
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
                            imageInput.removeAttribute('name');
                            isSubmitting = true;
                            form.submit();
                        });
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>