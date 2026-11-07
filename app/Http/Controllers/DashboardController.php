<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = now()->toDateString();
        $role = auth()->user()->role?->name;

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD KASIR
        |--------------------------------------------------------------------------
        */

        if ($role === 'kasir') {
            $cashierId = auth()->id();

            $totalSalesToday = Transaction::where('user_id', $cashierId)
                ->whereDate('created_at', $today)
                ->where('status', 'completed')
                ->sum('total');

            $totalTransactionsToday = Transaction::where('user_id', $cashierId)
                ->whereDate('created_at', $today)
                ->where('status', 'completed')
                ->count();

            $totalProductsSoldToday = DB::table('transaction_details')
                ->join(
                    'transactions',
                    'transaction_details.transaction_id',
                    '=',
                    'transactions.id'
                )
                ->where('transactions.user_id', $cashierId)
                ->whereDate('transactions.created_at', $today)
                ->where('transactions.status', 'completed')
                ->sum('transaction_details.quantity');

            $averageTransaction = $totalTransactionsToday > 0
                ? $totalSalesToday / $totalTransactionsToday
                : 0;

            $startDate = now()->subDays(6)->startOfDay();

            $salesRows = Transaction::query()
                ->selectRaw('DATE(created_at) as sale_date, SUM(total) as daily_total')
                ->where('user_id', $cashierId)
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

            $topProducts = DB::table('transaction_details')
                ->join(
                    'transactions',
                    'transaction_details.transaction_id',
                    '=',
                    'transactions.id'
                )
                ->where('transactions.user_id', $cashierId)
                ->whereDate('transactions.created_at', $today)
                ->where('transactions.status', 'completed')
                ->select(
                    'transaction_details.product_name',
                    DB::raw('SUM(transaction_details.quantity) as total_sold')
                )
                ->groupBy('transaction_details.product_name')
                ->orderByDesc('total_sold')
                ->limit(5)
                ->get();

            $recentTransactions = Transaction::with('user')
                ->where('user_id', $cashierId)
                ->latest()
                ->limit(5)
                ->get();

            return view('dashboard.kasir', compact(
                'totalSalesToday',
                'totalTransactionsToday',
                'totalProductsSoldToday',
                'averageTransaction',
                'salesLabels',
                'salesData',
                'topProducts',
                'recentTransactions'
            ));
        }

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD GUDANG
        |--------------------------------------------------------------------------
        */

        if ($role === 'gudang') {
            $totalProducts = Product::where('is_active', true)->count();

            $lowStockCount = Product::where('is_active', true)
                ->where('stock', '>', 0)
                ->whereColumn('stock', '<=', 'minimum_stock')
                ->count();

            $outOfStockCount = Product::where('is_active', true)
                ->where('stock', '<=', 0)
                ->count();

            $incomingToday = StockMovement::whereDate('created_at', $today)
                ->where('movement_type', 'incoming')
                ->sum(DB::raw('ABS(quantity)'));

            $outgoingToday = StockMovement::whereDate('created_at', $today)
                ->whereIn('movement_type', ['outgoing', 'sale'])
                ->sum(DB::raw('ABS(quantity)'));

            $lowStockProducts = Product::where('is_active', true)
                ->where('stock', '>', 0)
                ->whereColumn('stock', '<=', 'minimum_stock')
                ->orderBy('stock')
                ->limit(5)
                ->get();

            $outOfStockProducts = Product::where('is_active', true)
                ->where('stock', '<=', 0)
                ->orderBy('name')
                ->limit(5)
                ->get();

            $recentStockMovements = StockMovement::with(['product', 'user'])
                ->latest()
                ->limit(10)
                ->get();

            return view('dashboard.gudang', compact(
                'totalProducts',
                'lowStockCount',
                'outOfStockCount',
                'incomingToday',
                'outgoingToday',
                'lowStockProducts',
                'outOfStockProducts',
                'recentStockMovements'
            ));
        }

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD ADMIN / OWNER
        |--------------------------------------------------------------------------
        */

        if ($role === 'owner') {
            $totalSalesToday = Transaction::whereDate('created_at', $today)
                ->where('status', 'completed')
                ->sum('total');

            $totalTransactionsToday = Transaction::whereDate('created_at', $today)
                ->where('status', 'completed')
                ->count();

            $totalProductsSoldToday = DB::table('transaction_details')
                ->join(
                    'transactions',
                    'transaction_details.transaction_id',
                    '=',
                    'transactions.id'
                )
                ->whereDate('transactions.created_at', $today)
                ->where('transactions.status', 'completed')
                ->sum('transaction_details.quantity');

            $averageTransaction = $totalTransactionsToday > 0
                ? $totalSalesToday / $totalTransactionsToday
                : 0;

            $totalProducts = Product::where('is_active', true)->count();

            $lowStockCount = Product::where('is_active', true)
                ->where('stock', '>', 0)
                ->whereColumn('stock', '<=', 'minimum_stock')
                ->count();

            $outOfStockCount = Product::where('is_active', true)
                ->where('stock', '<=', 0)
                ->count();

            $incomingToday = StockMovement::whereDate('created_at', $today)
                ->where('movement_type', 'incoming')
                ->sum(DB::raw('ABS(quantity)'));

            $outgoingToday = StockMovement::whereDate('created_at', $today)
                ->whereIn('movement_type', ['outgoing', 'sale'])
                ->sum(DB::raw('ABS(quantity)'));

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

            $lowStockProducts = Product::where('is_active', true)
                ->where('stock', '>', 0)
                ->whereColumn('stock', '<=', 'minimum_stock')
                ->orderBy('stock')
                ->limit(5)
                ->get();

            $outOfStockProducts = Product::where('is_active', true)
                ->where('stock', '<=', 0)
                ->orderBy('name')
                ->limit(5)
                ->get();

            $recentTransactions = Transaction::with('user')
                ->latest()
                ->limit(5)
                ->get();

            $recentStockMovements = StockMovement::with(['product', 'user'])
                ->latest()
                ->limit(10)
                ->get();

            return view('dashboard.owner', compact(
                'totalSalesToday',
                'totalTransactionsToday',
                'totalProductsSoldToday',
                'averageTransaction',
                'totalProducts',
                'lowStockCount',
                'outOfStockCount',
                'incomingToday',
                'outgoingToday',
                'salesLabels',
                'salesData',
                'topProducts',
                'lowStockProducts',
                'outOfStockProducts',
                'recentTransactions',
                'recentStockMovements'
            ));
        }

        abort(403, 'Role akun tidak memiliki akses ke dashboard.');
    }
}
