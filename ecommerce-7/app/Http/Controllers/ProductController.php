<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

/**
 * ProductController
 *
 * Controller untuk mengelola data produk.
 * Menangani operasi CRUD (Create, Read, Update, Delete)
 * pada resource Product di panel admin.
 *
 * @package App\Http\Controllers
 */
class ProductController extends Controller
{
    /**
     * Menampilkan daftar semua produk dengan fitur pencarian dan pagination.
     *
     * Mengambil data produk beserta relasi kategorinya, diurutkan
     * dari yang terbaru. Mendukung pencarian berdasarkan nama produk
     * melalui query parameter 'search'.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View admin.products.index
     *
     * @example
     * // Tanpa pencarian  : /admin/products
     * // Dengan pencarian : /admin/products?search=laptop
     * //
     * // Data yang tersedia di view:
     * // $products          => kumpulan produk (10 per halaman)
     * // $products[0]->name => "Laptop Asus"
     * // $products[0]->product_category->name => "Elektronik"
     */
    public function index(Request $request)
    {
        $products = Product::with('product_category')
                        ->orderBy('created_at', 'desc');

        // Filter pencarian berdasarkan nama produk
        // Menggunakan LIKE untuk pencarian sebagian kata
        // Contoh: search="lap" akan menemukan "Laptop", "Laptop Gaming", dll
        if ($request->has('search')) {
            $products->where('name', 'like', '%' . $request->search . '%');
        }

        // Paginasi 10 produk per halaman
        $products = $products->paginate(10);

        // Menampilkan halaman product
        return view('admin.products.index', compact('products'));
    }

    /**
     * Menampilkan form untuk membuat produk baru.
     *
     * Mengambil semua data kategori produk untuk ditampilkan
     * sebagai pilihan dropdown pada form.
     *
     * @return \Illuminate\View\View admin.products.create
     *
     * @example
     * // Data yang tersedia di view:
     * // $productCategories => semua kategori produk
     */
    public function create()
    {
        $productCategories = ProductCategory::all();
        return view('admin.products.create', compact('productCategories'));
    }

    /**
     * Menyimpan produk baru ke database.
     *
     * Melakukan validasi input lalu menyimpan data produk baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     *
     * Validasi:
     * - name  : wajib diisi
     * - price : wajib diisi, harus berupa angka
     * - stock : wajib diisi, harus berupa bilangan bulat
     *
     * @note Method ini menggunakan $request->all() untuk mass assignment.
     *       Pastikan kolom yang tidak boleh diisi sudah didaftarkan
     *       di $guarded pada Model Product, atau gunakan $request->validated()
     *       untuk keamanan yang lebih baik.
     *
     * @todo Redirect saat ini mengarah ke 'products.index' (tanpa prefix admin.)
     *       Sebaiknya diperbaiki menjadi 'admin.products.index' agar konsisten.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        Product::create($request->all());

        // @todo: Seharusnya redirect ke 'admin.products.index'
        return redirect()->route('products.index');
    }

    /**
     * Menampilkan detail satu produk.
     *
     * Menggunakan Route Model Binding — Laravel otomatis mencari
     * Product berdasarkan ID dari URL.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\View\View products.show
     *
     * @example
     * // URL     : /products/1
     * // Laravel otomatis inject: $product = Product::find(1)
     *
     * @note Method ini mengarah ke view 'products.show' (bukan admin),
     *       yang berarti halaman detail produk ini ditujukan untuk
     *       halaman publik, bukan panel admin.
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Menampilkan form untuk mengedit produk yang sudah ada.
     *
     * Mengambil semua kategori produk untuk pilihan dropdown,
     * serta data produk yang akan diedit.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\View\View admin.products.edit
     *
     * @example
     * // URL     : /admin/products/1/edit
     * // Data yang tersedia di view:
     * // $product           => data produk yang akan diedit
     * // $productCategories => semua kategori untuk dropdown
     */
    public function edit(Product $product)
    {
        $productCategories = ProductCategory::all();
        return view('admin.products.edit', compact('product', 'productCategories'));
    }

    /**
     * Memperbarui data produk di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product       $product
     * @return void
     *
     * @note Method ini belum diimplementasikan
     * @todo Tambahkan validasi input dan logika update produk
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Menghapus produk dari database.
     *
     * @param  \App\Models\Product  $product
     * @return void
     *
     * @note Method ini belum diimplementasikan
     * @todo Tambahkan logika penghapusan produk beserta
     *       penanganan relasi (misalnya hapus gambar terkait)
     */
    public function destroy(Product $product)
    {
        //
    }
}
