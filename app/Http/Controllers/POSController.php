<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class POSController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)->get();
        $products = Product::where('is_active', true)
            ->where('quantity', '>', 0)
            ->with('category')
            ->get();

        return view('pos.index', compact('categories', 'products'));
    }

    public function searchProducts(Request $request)
    {
        $search = $request->get('search', '');
        $categoryId = $request->get('category_id');

        $query = Product::where('is_active', true)
            ->where('quantity', '>', 0)
            ->with('category');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->take(50)->get();

        return response()->json($products);
    }

    public function getProduct(Product $product)
    {
        return response()->json($product->load('category'));
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.discount' => 'nullable|numeric|min:0',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,card,upi',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $subtotal = 0;
            $itemsData = [];

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}. Available: {$product->quantity}");
                }

                $itemDiscount = $item['discount'] ?? 0;
                $itemTotal = ($product->selling_price * $item['quantity']) - $itemDiscount;
                $subtotal += $itemTotal;

                $itemsData[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'discount' => $itemDiscount,
                    'total' => $itemTotal,
                ];
            }

            $discount = $validated['discount'] ?? 0;
            $tax = $validated['tax'] ?? 0;
            $total = $subtotal - $discount + $tax;
            $changeAmount = $validated['paid_amount'] - $total;

            if ($changeAmount < 0) {
                throw new \Exception('Paid amount is less than total.');
            }

            $sale = Sale::create([
                'invoice_number' => Sale::generateInvoiceNumber(),
                'user_id' => auth()->id(),
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'total' => $total,
                'paid_amount' => $validated['paid_amount'],
                'change_amount' => $changeAmount,
                'payment_method' => $validated['payment_method'],
                'status' => 'completed',
                'notes' => $validated['notes'],
            ]);

            foreach ($itemsData as $itemData) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $itemData['product']->id,
                    'product_name' => $itemData['product']->name,
                    'unit_price' => $itemData['product']->selling_price,
                    'cost_price' => $itemData['product']->cost_price,
                    'quantity' => $itemData['quantity'],
                    'discount' => $itemData['discount'],
                    'total' => $itemData['total'],
                ]);

                $itemData['product']->decrement('quantity', $itemData['quantity']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sale completed successfully!',
                'sale' => $sale->load('items'),
                'redirect' => route('pos.receipt', $sale),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function receipt(Sale $sale)
    {
        $sale->load('items', 'user');
        return view('pos.receipt', compact('sale'));
    }

    public function salesHistory(Request $request)
    {
        $query = Sale::with('user', 'items');

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sales = $query->latest()->paginate(20);

        return view('pos.history', compact('sales'));
    }

    public function cancelSale(Sale $sale)
    {
        if ($sale->status === 'cancelled') {
            return back()->with('error', 'Sale is already cancelled.');
        }

        DB::beginTransaction();

        try {
            foreach ($sale->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('quantity', $item->quantity);
                }
            }

            $sale->update(['status' => 'cancelled']);

            DB::commit();

            return back()->with('success', 'Sale cancelled and stock restored.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to cancel sale: ' . $e->getMessage());
        }
    }
}
