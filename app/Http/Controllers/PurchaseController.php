<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\InventoryBatch;
use App\Models\Setting;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with('supplier')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhereHas('supplier', fn ($s) => $s->where('name', 'like', "%{$search}%")
                                                         ->orWhere('company', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('supplier')) {
            $query->where('supplier_id', $request->supplier);
        }

        $purchases = $query->paginate(15);
        $suppliers  = Supplier::where('is_active', true)->orderBy('name')->get();

        return view('purchases.index', compact('purchases', 'suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::where('is_active', true)
            ->withSum(['purchases as total_billed' => fn ($q) => $q->where('status', 'received')], 'total')
            ->withSum(['purchases as total_paid'   => fn ($q) => $q->where('status', 'received')], 'paid_amount')
            ->orderBy('name')
            ->get();

        $products       = Product::where('is_active', true)->orderBy('name')->get();
        $paymentMethods = Setting::getPaymentMethods();

        return view('purchases.create', compact('suppliers', 'products', 'paymentMethods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id'             => 'required|exists:suppliers,id',
            'purchase_date'           => 'required|date',
            'payment_method'          => 'required|string|max:50',
            'status'                  => 'required|in:ordered,received',
            'discount'                => 'nullable|numeric|min:0',
            'tax'                     => 'nullable|numeric|min:0',
            'paid_amount'             => 'required|numeric|min:0',
            'notes'                   => 'nullable|string',
            'items'                   => 'required|array|min:1',
            'items.*.product_id'      => 'required|exists:products,id',
            'items.*.cost_price'      => 'required|numeric|min:0',
            'items.*.quantity'        => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $subtotal = 0;
            $itemsData = [];

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $lineTotal = $item['cost_price'] * $item['quantity'];
                $subtotal += $lineTotal;
                $itemsData[] = [
                    'product'    => $product,
                    'cost_price' => $item['cost_price'],
                    'quantity'   => $item['quantity'],
                    'total'      => $lineTotal,
                ];
            }

            $discount   = $validated['discount'] ?? 0;
            $tax        = $validated['tax'] ?? 0;
            $total      = $subtotal - $discount + $tax;
            $purchaseDate = \Carbon\Carbon::parse($validated['purchase_date']);

            $purchase = Purchase::create([
                'reference_number' => Purchase::generateReferenceNumber($purchaseDate),
                'supplier_id'      => $validated['supplier_id'],
                'user_id'          => auth()->id(),
                'purchase_date'    => $validated['purchase_date'],
                'subtotal'         => $subtotal,
                'discount'         => $discount,
                'tax'              => $tax,
                'total'            => $total,
                'paid_amount'      => $validated['paid_amount'],
                'payment_method'   => $validated['payment_method'],
                'status'           => $validated['status'],
                'notes'            => $validated['notes'] ?? null,
            ]);

            foreach ($itemsData as $itemData) {
                $purchaseItem = PurchaseItem::create([
                    'purchase_id'  => $purchase->id,
                    'product_id'   => $itemData['product']->id,
                    'product_name' => $itemData['product']->name,
                    'cost_price'   => $itemData['cost_price'],
                    'quantity'     => $itemData['quantity'],
                    'total'        => $itemData['total'],
                ]);

                // If received immediately, update product stock and create FIFO batch
                if ($validated['status'] === 'received') {
                    $itemData['product']->increment('quantity', $itemData['quantity']);
                    $itemData['product']->update(['cost_price' => $itemData['cost_price']]);
                    
                    // Create FIFO inventory batch
                    $itemData['product']->createInventoryBatch(
                        $itemData['quantity'],
                        $itemData['cost_price'],
                        $validated['purchase_date'] ?? now()->toDateString(),
                        $purchaseItem->id,
                        'Purchase #' . $purchase->reference_number
                    );
                }
            }

            DB::commit();

            return redirect()->route('purchases.show', $purchase)
                ->with('success', 'Purchase recorded successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to save purchase: ' . $e->getMessage());
        }
    }

    public function show(Purchase $purchase)
    {
        $purchase->load('supplier', 'user', 'items.product');
        $paymentMethods = Setting::getPaymentMethods();
        $supplierTotalDue = $purchase->supplier->totalDue();

        return view('purchases.show', compact('purchase', 'paymentMethods', 'supplierTotalDue'));
    }

    public function pay(Request $request, Purchase $purchase)
    {
        if (! $purchase->isReceived()) {
            return back()->with('error', 'Payments can only be recorded on received purchases.');
        }

        $due = round((float) $purchase->total - (float) $purchase->paid_amount, 2);

        if ($due <= 0) {
            return back()->with('error', 'This purchase has no outstanding due.');
        }

        $request->validate([
            'payment_amount' => "required|numeric|min:0.01|max:{$due}",
            'payment_method' => 'required|string|max:50',
        ]);

        $purchase->update([
            'paid_amount'    => (float) $purchase->paid_amount + (float) $request->payment_amount,
            'payment_method' => $request->payment_method,
        ]);

        $remaining = round($purchase->total - $purchase->fresh()->paid_amount, 2);
        $msg = 'Payment of ' . number_format($request->payment_amount, 2) . ' recorded.';
        $msg .= $remaining > 0
            ? ' Remaining due: ' . number_format($remaining, 2) . '.'
            : ' Purchase is now fully paid.';

        return back()->with('success', $msg);
    }

    public function edit(Purchase $purchase)
    {
        if (! $purchase->isOrdered()) {
            return redirect()->route('purchases.show', $purchase)
                ->with('error', 'Only purchases with status "ordered" can be edited.');
        }

        $purchase->load('items.product');
        $suppliers      = Supplier::where('is_active', true)->orderBy('name')->get();
        $products       = Product::where('is_active', true)->orderBy('name')->get();
        $paymentMethods = Setting::getPaymentMethods();

        // Pre-encode for safe injection into the JS variable
        $existingItemsJson = json_encode($purchase->items->map(function ($i) {
            return [
                'product_id' => $i->product_id,
                'cost_price' => (float) $i->cost_price,
                'quantity'   => (int) $i->quantity,
            ];
        })->values()->all());

        return view('purchases.edit', compact('purchase', 'suppliers', 'products', 'paymentMethods', 'existingItemsJson'));
    }

    public function update(Request $request, Purchase $purchase)
    {
        if (! $purchase->isOrdered()) {
            return redirect()->route('purchases.show', $purchase)
                ->with('error', 'Only purchases with status "ordered" can be edited.');
        }

        $validated = $request->validate([
            'supplier_id'        => 'required|exists:suppliers,id',
            'purchase_date'      => 'required|date',
            'payment_method'     => 'required|string|max:50',
            'discount'           => 'nullable|numeric|min:0',
            'tax'                => 'nullable|numeric|min:0',
            'paid_amount'        => 'required|numeric|min:0',
            'notes'              => 'nullable|string',
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.cost_price' => 'required|numeric|min:0',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $subtotal  = 0;
            $itemsData = [];

            foreach ($validated['items'] as $item) {
                $product   = Product::findOrFail($item['product_id']);
                $lineTotal = $item['cost_price'] * $item['quantity'];
                $subtotal += $lineTotal;
                $itemsData[] = [
                    'product'    => $product,
                    'cost_price' => $item['cost_price'],
                    'quantity'   => $item['quantity'],
                    'total'      => $lineTotal,
                ];
            }

            $discount = $validated['discount'] ?? 0;
            $tax      = $validated['tax'] ?? 0;
            $total    = $subtotal - $discount + $tax;

            $purchase->update([
                'supplier_id'    => $validated['supplier_id'],
                'purchase_date'  => $validated['purchase_date'],
                'subtotal'       => $subtotal,
                'discount'       => $discount,
                'tax'            => $tax,
                'total'          => $total,
                'paid_amount'    => $validated['paid_amount'],
                'payment_method' => $validated['payment_method'],
                'notes'          => $validated['notes'] ?? null,
            ]);

            // Replace all items with the new set
            $purchase->items()->delete();

            foreach ($itemsData as $itemData) {
                PurchaseItem::create([
                    'purchase_id'  => $purchase->id,
                    'product_id'   => $itemData['product']->id,
                    'product_name' => $itemData['product']->name,
                    'cost_price'   => $itemData['cost_price'],
                    'quantity'     => $itemData['quantity'],
                    'total'        => $itemData['total'],
                ]);
            }

            DB::commit();

            return redirect()->route('purchases.show', $purchase)
                ->with('success', 'Purchase updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update purchase: ' . $e->getMessage());
        }
    }

    public function receive(Request $request, Purchase $purchase)
    {
        if (! $purchase->isOrdered()) {
            return back()->with('error', 'Only purchases with status "ordered" can be received.');
        }

        $request->validate([
            'payment_now'    => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:50',
        ]);

        $due        = (float) $purchase->total - (float) $purchase->paid_amount;
        $paymentNow = min((float) ($request->input('payment_now', 0)), $due); // cap at due amount
        $newPaid    = (float) $purchase->paid_amount + $paymentNow;

        try {
            DB::beginTransaction();

            $purchase->load('items.product');

            foreach ($purchase->items as $item) {
                $item->product->increment('quantity', $item->quantity);
                $item->product->update(['cost_price' => $item->cost_price]);
                
                // Create FIFO inventory batch
                $item->product->createInventoryBatch(
                    $item->quantity,
                    $item->cost_price,
                    $purchase->purchase_date,
                    $item->id,
                    'Purchase #' . $purchase->reference_number . ' (Received)'
                );
            }

            $updateData = ['status' => 'received', 'paid_amount' => $newPaid];

            // Update payment method only if a new payment was made
            if ($paymentNow > 0 && $request->filled('payment_method')) {
                $updateData['payment_method'] = $request->payment_method;
            }

            $purchase->update($updateData);

            DB::commit();

            $remainingDue = $purchase->total - $newPaid;
            $msg = 'Purchase marked as received and stock updated.';
            if ($paymentNow > 0) {
                $msg .= ' Payment of ' . number_format($paymentNow, 2) . ' recorded.';
            }
            if ($remainingDue > 0.001) {
                $msg .= ' Remaining due: ' . number_format($remainingDue, 2) . '.';
            }

            return back()->with('success', $msg);
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to receive purchase: ' . $e->getMessage());
        }
    }

    public function cancel(Purchase $purchase)
    {
        if ($purchase->isCancelled()) {
            return back()->with('error', 'Purchase is already cancelled.');
        }

        $wasReceived = $purchase->isReceived();

        try {
            DB::beginTransaction();

            // If stock was already added, reverse it
            if ($wasReceived) {
                $purchase->load('items.product');
                foreach ($purchase->items as $item) {
                    $newQty = max(0, $item->product->quantity - $item->quantity);
                    $item->product->update(['quantity' => $newQty]);
                }
            }

            $purchase->update(['status' => 'cancelled']);

            DB::commit();

            $msg = $wasReceived ? 'Purchase cancelled and stock reversed.' : 'Purchase cancelled.';
            return back()->with('success', $msg);
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to cancel purchase: ' . $e->getMessage());
        }
    }
}
