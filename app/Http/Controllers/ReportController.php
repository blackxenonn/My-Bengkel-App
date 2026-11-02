<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        // Periode default: bulan ini
        $validated = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $startDate = $validated['start_date']
            ?? now()->startOfMonth()->toDateString();

        $endDate = $validated['end_date']
            ?? now()->toDateString();

        // Query transaksi yang berhasil
        $completedTransactions = Transaction::query()
            ->where('status', 'completed')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        // Ringkasan penjualan
        $totalSales = (clone $completedTransactions)->sum('total');

        $totalTransactions = (clone $completedTransactions)->count();

        // Total produk terjual
        $totalProductsSold = DB::table('transaction_details')
            ->join(
                'transactions',
                'transaction_details.transaction_id',
                '=',
                'transactions.id'
            )
            ->where('transactions.status', 'completed')
            ->whereDate('transactions.created_at', '>=', $startDate)
            ->whereDate('transactions.created_at', '<=', $endDate)
            ->sum('transaction_details.quantity');

        // Jumlah produk aktif yang terdaftar
        $registeredProducts = Product::where('is_active', true)->count();

        // Produk terlaris
        $topProducts = DB::table('transaction_details')
            ->join(
                'transactions',
                'transaction_details.transaction_id',
                '=',
                'transactions.id'
            )
            ->where('transactions.status', 'completed')
            ->whereDate('transactions.created_at', '>=', $startDate)
            ->whereDate('transactions.created_at', '<=', $endDate)
            ->select(
                'transaction_details.product_name',
                DB::raw('SUM(transaction_details.quantity) as total_sold')
            )
            ->groupBy('transaction_details.product_name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // Riwayat transaksi berhasil
        $transactions = (clone $completedTransactions)
            ->with('user')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('reports.index', compact(
            'startDate',
            'endDate',
            'totalSales',
            'totalTransactions',
            'totalProductsSold',
            'registeredProducts',
            'topProducts',
            'transactions'
        ));
    }
}
