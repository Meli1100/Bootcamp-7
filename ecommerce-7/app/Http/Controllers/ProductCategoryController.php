<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;

/**
 * ProductCategoryController
 *
 * Controller untuk mengelola data kategori produk.
 * Menangani operasi CRUD (Create, Read, Update, Delete)
 * pada resource ProductCategory.
 *
 * @package App\Http\Controllers
 */
class ProductCategoryController extends Controller
{
    /**
     * Menampilkan daftar semua kategori produk.
     *
     * Mengambil semua data kategori beserta:
     * - Jumlah produk di setiap kategori (products_count)
     * - Total stok produk di setiap kategori (total_stock)
     *
     * @return \Illuminate\View\View admin.product-category.index
     *
     * @example
     * // Data yang tersedia di view:
     * // $productCategories[0]->name           => "Elektronik"
     * // $productCategories[0]->products_count  => 10
     * // $productCategories[0]->total_stock     => 150
     */
    public function index()
    {
        $productCategories = ProductCategory::withCount('products')
                                ->withSum('products as total_stock', 'stock')
                                ->get();
        return view('admin.product-category.index', compact('productCategories'));
    }

    /**
     * Menampilkan form untuk membuat kategori produk baru.
     *
     * @return \Illuminate\View\View admin.product-category.create
     */
    public function create()
    {
        return view('admin.product-category.create');
    }

    /**
     * Menyimpan kategori produk baru ke database.
     *
     * Melakukan validasi input, generate slug otomatis dari nama kategori,
     * lalu menyimpan data ke tabel product_categories.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     *
     * Validasi:
     * - name        : wajib diisi, string, maks 255 karakter, unik di tabel product_categories
     * - description : opsional, string
     *
     * @example
     * // Input  : name = "Elektronik Rumah"
     * // Output slug : "elektronik-rumah"
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:product_categories',
            'description' => 'nullable|string',
        ]);

        // Generate slug dari name
        // Contoh: "Elektronik Rumah" → "elektronik-rumah"
        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);

        ProductCategory::create($validated);

        return redirect()->route('admin.product-categories.index')
                        ->with('success', 'Kategori produk berhasil ditambahkan');
    }

    /**
     * Menampilkan detail satu kategori produk.
     *
     * @param  \App\Models\ProductCategory  $productCategory
     * @return void
     *
     * @note Method ini belum diimplementasikan
     */
    public function show(ProductCategory $productCategory)
    {
        //
    }

    /**
     * Menampilkan form untuk mengedit kategori produk yang sudah ada.
     *
     * Menggunakan Route Model Binding — Laravel otomatis mencari
     * ProductCategory berdasarkan ID dari URL.
     *
     * @param  \App\Models\ProductCategory  $productCategory
     * @return \Illuminate\View\View admin.product-category.edit
     *
     * @example
     * // URL     : /admin/product-categories/1/edit
     * // Laravel otomatis inject: $productCategory = ProductCategory::find(1)
     */
    public function edit(ProductCategory $productCategory)
    {
        return view('admin.product-category.edit', compact('productCategory'));
    }

    /**
     * Memperbarui data kategori produk di database.
     *
     * Melakukan validasi input, generate ulang slug dari nama baru,
     * lalu mengupdate data kategori yang dipilih.
     *
     * @param  \Illuminate\Http\Request     $request
     * @param  \App\Models\ProductCategory  $productCategory
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     *
     * Validasi:
     * - name        : wajib diisi, string, maks 255 karakter, unik kecuali
     *                 untuk kategori yang sedang diedit (ignore current ID)
     * - description : opsional, string
     *
     * @example
     * // Validasi unique mengabaikan ID kategori saat ini agar tidak
     * // dianggap duplikat ketika nama tidak diubah:
     * // 'unique:product_categories,name,' . $productCategory->id
     */
    public function update(Request $request, ProductCategory $productCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:product_categories,name,' . $productCategory->id,
            'description' => 'nullable|string',
        ]);

        // Generate ulang slug dari name yang baru
        // Contoh: "Elektronik Baru" → "elektronik-baru"
        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);

        $productCategory->update($validated);

        return redirect()->route('admin.product-categories.index')
                        ->with('success', 'Kategori produk berhasil diperbarui');
    }

    /**
     * Menghapus kategori produk dari database.
     *
     * Menghapus kategori yang dipilih dan mengembalikan
     * response JSON (cocok untuk request AJAX/fetch dari frontend).
     *
     * @param  \App\Models\ProductCategory  $productCategory
     * @return \Illuminate\Http\JsonResponse
     *
     * @example
     * // Response sukses:
     * // {
     * //   "success": true,
     * //   "message": "Kategori produk berhasil dihapus"
     * // }
     *
     * @note Method ini mengembalikan JSON, berbeda dengan method lain
     *       yang mengembalikan redirect. Pastikan request dikirim
     *       menggunakan DELETE method (AJAX atau form dengan @method('DELETE'))
     */
    public function destroy(ProductCategory $productCategory)
    {
        $productCategory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori produk berhasil dihapus'
        ]);
    }
}
