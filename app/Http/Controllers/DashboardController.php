<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = now()->toDateString();

        // Total penjualan hari ini
        $totalSalesToday = Transaction::whereDate('created_at', $today)
            ->where('status', 'completed')
            ->sum('total');

        // Jumlah transaksi selesai hari ini
        $totalTransactionsToday = Transaction::whereDate('created_at', $today)
            ->where('status', 'completed')
            ->count();

        // Jumlah produk aktif
        $totalProducts = Product::where('is_active', true)->count();

        // Produk yang stoknya mencapai atau di bawah batas minimum
        $lowStockProducts = Product::where('is_active', true)
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->count();

        return view('dashboard', compact(
            'totalSalesToday',
            'totalTransactionsToday',
            'totalProducts',
            'lowStockProducts'
        ));
    }
}
