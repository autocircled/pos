<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function daily(Request $request)
    {
        $date = $request->get('date', today()->format('Y-m-d'));

        $sales = Sale::with('items', 'user')
            ->whereDate('created_at', $date)
            ->where('status', 'completed')
            ->get();

        $summary = [
            'total_sales' => $sales->sum('total'),
            'total_transactions' => $sales->count(),
            'total_items_sold' => $sales->sum(fn($sale) => $sale->items->sum('quantity')),
            'total_discount' => $sales->sum('discount') + $sales->sum(fn($sale) => $sale->items->sum('discount')),
            'total_profit' => $sales->sum(fn($sale) => $sale->getProfit()),
            'cash_sales' => $sales->where('payment_method', 'cash')->sum('total'),
            'card_sales' => $sales->where('payment_method', 'card')->sum('total'),
            'upi_sales' => $sales->where('payment_method', 'upi')->sum('total'),
        ];

        $topProducts = SaleItem::select('product_id', 'product_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(total) as total_amount'))
            ->whereHas('sale', fn($q) => $q->whereDate('created_at', $date)->where('status', 'completed'))
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_qty')
            ->take(10)
            ->get();

        $hourlySales = Sale::selectRaw('HOUR(created_at) as hour, SUM(total) as total, COUNT(*) as count')
            ->whereDate('created_at', $date)
            ->where('status', 'completed')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        return view('reports.daily', compact('date', 'sales', 'summary', 'topProducts', 'hourlySales'));
    }

    public function monthly(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $startDate = \Carbon\Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $sales = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed');

        $summary = [
            'total_sales' => (clone $sales)->sum('total'),
            'total_transactions' => (clone $sales)->count(),
            'average_sale' => (clone $sales)->avg('total') ?? 0,
            'total_discount' => (clone $sales)->sum('discount'),
        ];

        $salesWithItems = Sale::with('items')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->get();

        $summary['total_profit'] = $salesWithItems->sum(fn($sale) => $sale->getProfit());
        $summary['total_items_sold'] = $salesWithItems->sum(fn($sale) => $sale->items->sum('quantity'));

        $dailySales = Sale::selectRaw('DATE(created_at) as date, SUM(total) as total, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $topProducts = SaleItem::select('product_id', 'product_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(total) as total_amount'))
            ->whereHas('sale', fn($q) => $q->whereBetween('created_at', [$startDate, $endDate])->where('status', 'completed'))
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_amount')
            ->take(10)
            ->get();

        $categoryWiseSales = SaleItem::select('products.category_id', 'categories.name as category_name', DB::raw('SUM(sale_items.total) as total'))
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereHas('sale', fn($q) => $q->whereBetween('created_at', [$startDate, $endDate])->where('status', 'completed'))
            ->groupBy('products.category_id', 'categories.name')
            ->orderByDesc('total')
            ->get();

        $paymentMethodSales = Sale::selectRaw('payment_method, SUM(total) as total, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->groupBy('payment_method')
            ->get();

        return view('reports.monthly', compact(
            'month', 'year', 'summary', 'dailySales', 'topProducts', 
            'categoryWiseSales', 'paymentMethodSales', 'startDate', 'endDate'
        ));
    }

    public function inventory()
    {
        $products = Product::with('category')
            ->orderBy('quantity')
            ->get();

        $summary = [
            'total_products' => $products->count(),
            'total_stock_value' => $products->sum(fn($p) => $p->quantity * $p->cost_price),
            'total_retail_value' => $products->sum(fn($p) => $p->quantity * $p->selling_price),
            'low_stock_count' => $products->filter(fn($p) => $p->isLowStock())->count(),
            'out_of_stock_count' => $products->filter(fn($p) => $p->quantity === 0)->count(),
        ];

        $categoryStock = Category::withSum('products', 'quantity')
            ->withCount('products')
            ->get();

        return view('reports.inventory', compact('products', 'summary', 'categoryStock'));
    }

    public function profit(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $sales = Sale::with('items')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->get();

        $totalRevenue = $sales->sum('total');
        $totalCost = $sales->sum(fn($sale) => $sale->items->sum(fn($item) => $item->cost_price * $item->quantity));
        $totalProfit = $sales->sum(fn($sale) => $sale->getProfit());
        $profitMargin = $totalRevenue > 0 ? ($totalProfit / $totalRevenue) * 100 : 0;

        $dailyProfit = Sale::with('items')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->get()
            ->groupBy(fn($sale) => $sale->created_at->format('Y-m-d'))
            ->map(fn($daySales) => [
                'revenue' => $daySales->sum('total'),
                'profit' => $daySales->sum(fn($s) => $s->getProfit()),
            ]);

        $productProfit = SaleItem::select(
                'product_id', 
                'product_name',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(total) as revenue'),
                DB::raw('SUM((unit_price - cost_price) * quantity - discount) as profit')
            )
            ->whereHas('sale', fn($q) => $q->whereBetween('created_at', [$startDate, $endDate])->where('status', 'completed'))
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('profit')
            ->get();

        return view('reports.profit', compact(
            'startDate', 'endDate', 'totalRevenue', 'totalCost', 
            'totalProfit', 'profitMargin', 'dailyProfit', 'productProfit'
        ));
    }
}
