<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Dashboard', [
            'totalProducts'   => Product::count(),
            'totalCategories' => Category::count(),
            'totalSuppliers'  => Supplier::count(),
            'lowStockCount'   => Product::where('stock_quantity', '>', 0)
                                        ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
                                        ->count(),
            'outOfStockCount' => Product::where('stock_quantity', '<=', 0)->count(),
        ]);
    }
}