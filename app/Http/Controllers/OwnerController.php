<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\View\View;

class OwnerController extends Controller
{
    public function index(): View
    {
        $totalEmployees = User::whereHas('role', function ($query) {
            $query->whereIn('name', ['kasir', 'gudang']);
        })->count();

        $totalProducts = Product::count();

        $totalCategories = Category::count();

        $lowStockProducts = Product::whereColumn(
            'stock',
            '<=',
            'minimum_stock'
        )->count();

        $totalStockMovements = StockMovement::count();

        return view('owner.index', compact(
            'totalEmployees',
            'totalProducts',
            'totalCategories',
            'lowStockProducts',
            'totalStockMovements'
        ));
    }
}