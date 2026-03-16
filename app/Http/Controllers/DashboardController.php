<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $todaySales = Sale::whereDate('created_at', today())
            ->where('status', 'completed')
            ->sum('total');

        $monthSales = Sale::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'completed')
            ->sum('total');

        $totalProducts = Product::count();
        // Low stock: greater than 0 but less than or equal to alert quantity
        $lowStockProducts = Product::where('quantity', '>', 0)
            ->whereColumn('quantity', '<=', 'alert_quantity')
            ->count();

        $todayTransactions = Sale::whereDate('created_at', today())
            ->where('status', 'completed')
            ->count();

        $recentSales = Sale::with('user')
            ->where('status', 'completed')
            ->latest()
            ->take(5)
            ->get();

        $lowStockItems = Product::with('category')
            ->where('quantity', '>', 0)
            ->whereColumn('quantity', '<=', 'alert_quantity')
            ->orderBy('quantity')
            ->take(5)
            ->get();

        $topSellingProducts = SaleItem::select('product_id', 'product_name', DB::raw('SUM(quantity) as total_sold'))
            ->whereHas('sale', function ($query) {
                $query->whereMonth('created_at', now()->month)
                    ->where('status', 'completed');
            })
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        $salesChart = Sale::selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->where('status', 'completed')
            ->whereDate('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('dashboard.index', compact(
            'todaySales',
            'monthSales',
            'totalProducts',
            'lowStockProducts',
            'todayTransactions',
            'recentSales',
            'lowStockItems',
            'topSellingProducts',
            'salesChart'
        ));
    }
}
