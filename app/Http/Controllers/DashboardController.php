<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = now()->toDateString();

        // Ringkasan penjualan hari ini
        $totalSalesToday = Transaction::whereDate('created_at', $today)
            ->where('status', 'completed')
            ->sum('total');

        $totalTransactionsToday = Transaction::whereDate('created_at', $today)
            ->where('status', 'completed')
            ->count();

        // Ringkasan produk dan stok
        $totalProducts = Product::where('is_active', true)->count();

        $lowStockProducts = Product::where('is_active', true)
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->count();

        // Data grafik penjualan 7 hari terakhir
        $startDate = now()->subDays(6)->startOfDay();

        $salesRows = Transaction::query()
            ->selectRaw('DATE(created_at) as sale_date, SUM(total) as daily_total')
            ->where('status', 'completed')
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', now())
            ->groupBy('sale_date')
            ->orderBy('sale_date')
            ->get()
            ->keyBy('sale_date');

        $salesLabels = [];
        $salesData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();

            $salesLabels[] = now()->subDays($i)->format('d M');
            $salesData[] = (float) ($salesRows[$date]->daily_total ?? 0);
        }

        // 5 produk terlaris selama 7 hari terakhir
        $topProducts = DB::table('transaction_details')
            ->join(
                'transactions',
                'transaction_details.transaction_id',
                '=',
                'transactions.id'
            )
            ->where('transactions.status', 'completed')
            ->where('transactions.created_at', '>=', $startDate)
            ->where('transactions.created_at', '<=', now())
            ->select(
                'transaction_details.product_name',
                DB::raw('SUM(transaction_details.quantity) as total_sold')
            )
            ->groupBy('transaction_details.product_name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalSalesToday',
            'totalTransactionsToday',
            'totalProducts',
            'lowStockProducts',
            'salesLabels',
            'salesData',
            'topProducts'
        ));
    }
}
