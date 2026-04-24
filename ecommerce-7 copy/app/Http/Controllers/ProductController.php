<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
        {
        $products = Product::with('product_category')
                        ->orderBy('created_at', 'desc');
        if ($request->has('search')) {
            $products->where('name', 'like', '%' . $request->search . '%');
        }
        $products = $products->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productCategories = ProductCategory::all();
        return view('admin.products.create', compact('productCategories'));
    }

    /**
     * Helper to save uploaded or base64 image data.
     */
    private function saveProductImage(Request $request, ?string $existingImage = null)
    {
        if ($request->filled('cropped_image')) {
            if (!preg_match('/^data:image\/(png|jpeg|jpg|gif|webp);base64,/', $request->cropped_image, $matches)) {
                return $existingImage;
            }

            $imageData = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $request->cropped_image));
            if ($imageData === false) {
                return $existingImage;
            }

            $extension = $matches[1] === 'jpeg' ? 'jpg' : $matches[1];
            $imageName = time() . '_' . uniqid() . '.' . $extension;
            Storage::disk('images')->put('products/' . $imageName, $imageData);

            return 'products/' . $imageName;
        }

        if ($request->hasFile('image_file')) {
            $imageFile = $request->file('image_file');
            $imageName = time() . '_' . $imageFile->getClientOriginalName();
            Storage::disk('images')->putFileAs('products', $imageFile, $imageName);

            return 'products/' . $imageName;
        }

        return $existingImage;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'                => 'required|string|max:255',
            'description'         => 'required|string|max:1000',
            'price'               => 'required|numeric|min:0',
            'stock'               => 'required|integer|min:0',
            'image_file'          => 'required_without:cropped_image|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cropped_image'       => ['nullable', 'string', function ($attribute, $value, $fail) {
                if ($value && !preg_match('/^data:image\/(png|jpeg|jpg|gif|webp);base64,/', $value)) {
                    $fail('The '.$attribute.' must be a valid base64 encoded image.');
                }
            }],
            'product_category_id' => 'required|exists:product_categories,id',
        ]);

        $image_path = $this->saveProductImage($request);

        $slug = strtolower(str_replace(' ', '-', $request->name)) . '-' . uniqid();

        if ($request->image) {
            $imageData = $request->image;
            [$type, $imageData] = explode(';', $imageData);
            [$type, $extension] = explode('/', $imageData);
            $imageData = base64_decode(str_replace('base64,', '', $imageData));
            $imageName = time() . '_' . uniqid() . '.' . $extension;
            Storage::disk('images')->put('products/' . $imageName, $imageData);
            $image_path = 'products/' . $imageName;
        }

        Product::create([
            'name'                => $request->name,
            'slug'                => $slug,
            'description'         => $request->description,
            'price'               => $request->price,
            'stock'               => $request->stock,
            'image'               => $image_path,
            'product_category_id' => $request->product_category_id,
        ]);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $productCategories = ProductCategory::all();
        return view('admin.products.edit', compact('product', 'productCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'                => 'required|string|max:255',
            'description'         => 'required|string|max:1000',
            'price'               => 'required|numeric|min:0',
            'stock'               => 'required|integer|min:0',
            'image_file'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cropped_image'       => ['nullable', 'string', function ($attribute, $value, $fail) {
                if ($value && !preg_match('/^data:image\/(png|jpeg|jpg|gif|webp);base64,/', $value)) {
                    $fail('The '.$attribute.' must be a valid base64 encoded image.');
                }
            }],
            'product_category_id' => 'required|exists:product_categories,id',
        ]);

        $slug = strtolower(str_replace(' ', '-', $request->name)) . '-' . uniqid();
        $image_path = $this->saveProductImage($request, $product->image);

        if ($request->filled('image') && str_contains((string) $request->image, 'base64')) {
            $imageData = $request->image;
            [$type, $imageData] = explode(';', $imageData);
            [$type, $extension] = explode('/', $type);
            $imageData = base64_decode(str_replace('base64,', '', $imageData));
            $imageName = time() . '_' . uniqid() . '.' . $extension;

            Storage::disk('images')->put('products/' . $imageName, $imageData);
            $image_path = 'products/' . $imageName;

            if ($product->image && Storage::disk('images')->exists($product->image)) {
                Storage::disk('images')->delete($product->image);
            }
        }

        $product->update([
            'name'                => $request->name,
            'slug'                => $slug,
            'description'         => $request->description,
            'price'               => $request->price,
            'stock'               => $request->stock,
            'image'               => $image_path,
            'product_category_id' => $request->product_category_id,
        ]);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product with ID ' . $product->id . ' updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Product $product)
    {
        $id = $product->id;
        if($product->order_items()->count() > 0) {
            return redirect()
                ->route('admin.products.index')
                ->with('error', 'Product with ID ' . $id . ' Cannot be deleted because it has existing orders.');
        }

        if ($product->image && Storage::disk('images')->exists($product->image)) {
            Storage::disk('images')->delete($product->image);
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product with ID ' . $id . ' deleted successfully.');
    }
}
