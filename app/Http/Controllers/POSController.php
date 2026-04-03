<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\DuePayment;
use App\Models\InventoryBatch;
use App\Models\Setting;
use App\Http\Requests\StoreSaleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class POSController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)->get();
        $products = Product::where('is_active', true)
            ->where('quantity', '>', 0)
            ->with('category')
            ->orderBy('name')
            ->get();
        $paymentMethods = Setting::getPaymentMethods();

        return view('pos.index', compact('categories', 'products', 'paymentMethods'));
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
                    ->orWhere('barcode', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%");
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
        $allowedPaymentCodes = array_column(Setting::getPaymentMethods(), 'code');

        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.discount' => 'nullable|numeric|min:0',
            'sale_date' => 'nullable|date|before_or_equal:today',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'payment_method' => ['required', 'string', 'max:50', Rule::in($allowedPaymentCodes)],
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $saleDate = !empty($validated['sale_date'])
                ? Carbon::parse($validated['sale_date'])->setTimeFromTimeString(now()->format('H:i:s'))
                : now();

            $subtotal = 0;
            $itemsData = [];

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                // Check FIFO stock availability
                $fifoQuantity = $product->getFifoQuantity();
                if ($fifoQuantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}. Available: {$fifoQuantity}");
                }

                $itemDiscount = $item['discount'] ?? 0;
                $itemTotal = ($product->selling_price * $item['quantity']) - $itemDiscount;
                $subtotal += $itemTotal;

                // Get FIFO cost for this item
                $fifoBatches = $product->reduceFifoStock($item['quantity']);
                $averageCost = array_sum(array_column($fifoBatches, 'cost_price')) / count($fifoBatches);

                $itemsData[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'discount' => $itemDiscount,
                    'total' => $itemTotal,
                    'fifo_batches' => $fifoBatches,
                    'average_cost' => $averageCost,
                ];
            }

            $discount = $validated['discount'] ?? 0;
            $tax = $validated['tax'] ?? 0;
            $total = $subtotal - $discount + $tax;
            $paidAmount = $validated['paid_amount'];
            $changeAmount = $paidAmount - $total;
            $dueAmount = 0;
            $paymentStatus = 'paid';

            // Handle partial payments
            if ($changeAmount < 0) {
                $dueAmount = abs($changeAmount);
                $changeAmount = 0;
                $paymentStatus = $paidAmount > 0 ? 'partial' : 'due';
            }

            $sale = new Sale([
                'invoice_number' => Sale::generateInvoiceNumber($saleDate),
                'user_id' => auth()->id(),
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'total' => $total,
                'paid_amount' => $paidAmount,
                'change_amount' => $changeAmount,
                'due_amount' => $dueAmount,
                'payment_status' => $paymentStatus,
                'payment_method' => $validated['payment_method'],
                'status' => 'completed',
                'notes' => $validated['notes'],
            ]);
            $sale->created_at = $saleDate;
            $sale->updated_at = $saleDate;
            $sale->save();

            foreach ($itemsData as $itemData) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $itemData['product']->id,
                    'product_name' => $itemData['product']->name,
                    'unit_price' => $itemData['product']->selling_price,
                    'cost_price' => $itemData['average_cost'], // Use FIFO average cost
                    'quantity' => $itemData['quantity'],
                    'discount' => $itemData['discount'],
                    'total' => $itemData['total'],
                ]);

                // Note: Product quantity already updated by reduceFifoStock()
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
        $sale->load(['items', 'user', 'duePayments.user']);
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
                    // Restore stock to FIFO batches (oldest first)
                    $product->addFifoStock($item->quantity);
                }
            }

            // Delete related due payments when sale is cancelled
            $sale->duePayments()->delete();

            $sale->update(['status' => 'cancelled']);

            DB::commit();

            return back()->with('success', 'Sale cancelled and stock restored.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to cancel sale: ' . $e->getMessage());
        }
    }

    public function edit(Sale $sale)
    {
        if ($sale->status !== 'completed') {
            return back()->with('error', 'Only completed sales can be edited.');
        }

        $sale->load('items.product');
        $categories = Category::where('is_active', true)->get();
        $products = Product::where('is_active', true)
            ->with('category')
            ->get();
        $paymentMethods = Setting::getPaymentMethods();

        return view('pos.edit', compact('sale', 'categories', 'products', 'paymentMethods'));
    }

    public function update(Request $request, Sale $sale)
    {
        if ($sale->status !== 'completed') {
            return back()->with('error', 'Only completed sales can be updated.');
        }

        $allowedPaymentCodes = array_column(Setting::getPaymentMethods(), 'code');

        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.discount' => 'nullable|numeric|min:0',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'payment_method' => ['required', 'string', 'max:50', Rule::in($allowedPaymentCodes)],
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Restore original stock quantities
            foreach ($sale->items as $originalItem) {
                $product = Product::find($originalItem->product_id);
                if ($product) {
                    $product->increment('quantity', $originalItem->quantity);
                }
            }

            // Delete original sale items
            $sale->items()->delete();

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

                // Deduct new stock quantities
                $product->decrement('quantity', $item['quantity']);

                $itemsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_price' => $product->selling_price,
                    'cost_price' => $product->cost_price,
                    'quantity' => $item['quantity'],
                    'discount' => $itemDiscount,
                    'total' => $itemTotal,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $discount = $validated['discount'] ?? 0;
            $tax = $validated['tax'] ?? 0;
            $total = $subtotal - $discount + $tax;
            $paidAmount = $validated['paid_amount'];
            $changeAmount = max(0, $paidAmount - $total);
            $dueAmount = max(0, $total - $paidAmount);
            $paymentStatus = $dueAmount > 0 ? ($paidAmount > 0 ? 'partial' : 'due') : 'paid';

            // Update sale
            $sale->update([
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'total' => $total,
                'paid_amount' => $paidAmount,
                'change_amount' => $changeAmount,
                'due_amount' => $dueAmount,
                'payment_status' => $paymentStatus,
                'payment_method' => $validated['payment_method'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Create new sale items
            $sale->items()->createMany($itemsData);

            DB::commit();

            return redirect()->route('pos.history')
                ->with('success', 'Sale updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update sale: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function duePayments()
    {
        $query = Sale::with(['user', 'duePayments.user'])
            ->where('due_amount', '>', 0)
            ->where(function($q) {
                $q->where('payment_status', 'partial')
                  ->orWhere('payment_status', 'due');
            })
            ->havingRaw('(due_amount - (SELECT COALESCE(SUM(amount), 0) FROM due_payments WHERE sale_id = sales.id)) > 0');

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $sales = $query->latest()->paginate(20);
        
        return view('pos.due-payments', compact('sales'));
    }

    public function addDuePayment(Request $request, Sale $sale)
    {
        if ($sale->getRemainingDue() <= 0) {
            return back()->with('error', 'This sale has no due amount remaining.');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $sale->getRemainingDue(),
            'payment_method' => 'required|in:cash,card,bank_transfer,other',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();
            $remainingDue = $sale->getRemainingDue();
            $paymentAmount = $validated['amount'];

            DuePayment::create([
                'sale_id' => $sale->id,
                'amount' => $paymentAmount,
                'payment_method' => $validated['payment_method'],
                'notes' => $validated['notes'],
                'user_id' => auth()->id(),
            ]);

            // Update sale payment status if fully paid
            $totalRemainingDue = $remainingDue - $paymentAmount;
            if ($totalRemainingDue <= 0) {
                $sale->update(['payment_status' => 'paid']);
            } else {
                // Ensure status remains partial/due
                $sale->update(['payment_status' => 'partial']);
            }

            DB::commit();

            return back()->with('success', 'Payment recorded successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to record payment: ' . $e->getMessage());
        }
    }
}
