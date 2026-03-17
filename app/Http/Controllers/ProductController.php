<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%");
            });
        }

        if ($request->filled('company')) {
            $company = $request->company;
            $query->where('company', 'like', "%{$company}%");
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('stock')) {
            if ($request->stock === 'low') {
                // Low stock: greater than 0 but less than or equal to alert quantity
                $query->where('quantity', '>', 0)
                      ->whereColumn('quantity', '<=', 'alert_quantity');
            } elseif ($request->stock === 'out') {
                // Out of stock: exactly 0
                $query->where('quantity', 0);
            }
        }

        $products = $query->latest()->paginate(15);
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    public function create(Request $request)
    {
        $categories = Category::all();
        $duplicateProduct = null;

        if ($request->has('duplicate')) {
            $duplicateProduct = Product::find($request->duplicate);
            if (!$duplicateProduct) {
                return redirect()->route('products.create')->with('error', 'Product not found.');
            }
        }

        $sku = Product::generateSku();
        return view('products.create', compact('categories', 'sku', 'duplicateProduct'));
    }

    public function duplicate(Product $product)
    {
        return redirect()->route('products.create', ['duplicate' => $product->id]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'sku' => 'required|string|unique:products',
            'barcode' => 'nullable|string|unique:products',
            'description' => 'nullable|string',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'alert_quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $validated['image'] = $this->storeProductImage($request->file('image'));
        }

        $product = Product::create($validated);

        ActivityLog::log('created', Product::class, $product->id, null, $product->only($product->getFillable()), 'Product created');

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load('category', 'saleItems.sale');
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'barcode' => 'nullable|string|unique:products,barcode,' . $product->id,
            'description' => 'nullable|string',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'alert_quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            if ($product->image) {
                $this->deleteProductImage($product->image);
            }
            $validated['image'] = $this->storeProductImage($request->file('image'));
        }

        $oldValues = $product->only($product->getFillable());
        $product->update($validated);
        $newValues = $product->fresh()->only($product->getFillable());

        ActivityLog::log('updated', Product::class, $product->id, $oldValues, $newValues, 'Product updated');

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if (!auth()->user()->isAdmin()) {
            return back()->with('error', 'You do not have permission to delete products.');
        }

        $oldValues = $product->only($product->getFillable());
        $productId = $product->id;

        if ($product->image) {
            $this->deleteProductImage($product->image);
        }

        $product->delete();

        ActivityLog::log('deleted', Product::class, $productId, $oldValues, null, 'Product deleted');

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function adjustStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'adjustment' => 'required|integer',
            'type' => 'required|in:add,subtract',
        ]);

        if ($validated['type'] === 'add') {
            $product->increment('quantity', $validated['adjustment']);
        } else {
            if ($product->quantity < $validated['adjustment']) {
                return back()->with('error', 'Insufficient stock to subtract.');
            }
            $product->decrement('quantity', $validated['adjustment']);
        }

        return back()->with('success', 'Stock adjusted successfully.');
    }

    /**
     * Store uploaded image in public/uploads/products and return relative path.
     */
    private function storeProductImage($file): string
    {
        $dir = public_path('uploads/products');
        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }
        $name = uniqid('product_', true) . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $name);
        return 'uploads/products/' . $name;
    }

    /**
     * Delete product image file from public uploads.
     */
    private function deleteProductImage(string $path): void
    {
        $fullPath = public_path($path);
        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }
}
