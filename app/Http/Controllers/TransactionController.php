<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\CancellationRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim($request->input('search', ''));
        $date = $request->input('date');

        $query = Transaction::with('user')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {

                    // Cari berdasarkan nomor invoice.
                    $query->where('invoice_number', 'like', "%{$search}%")

                        // Cari berdasarkan nama kasir.
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%");
                        })

                        // Cari berdasarkan status transaksi.
                        ->orWhere('status', 'like', "%{$search}%");

                    // Padanan status dalam bahasa Indonesia.
                    $statusKeywords = [
                        'selesai' => 'completed',
                        'dibatalkan' => 'cancelled',
                        'batal' => 'cancelled',
                    ];

                    foreach ($statusKeywords as $keyword => $status) {
                        if (
                            stripos($keyword, $search) !== false ||
                            stripos($search, $keyword) !== false
                        ) {
                            $query->orWhere('status', $status);
                        }
                    }
                });
            })
            // Filter berdasarkan tanggal transaksi.
            ->when($date, function ($query) use ($date) {
                $query->whereDate('created_at', $date);
            })
            ->latest();

        $transactions = $query
            ->paginate(10)
            ->withQueryString();

        return view('transactions.index', compact(
            'transactions',
            'search',
            'date'
        ));
    }

    public function show(Transaction $transaction): View
    {
        $transaction->load([
            'user',
            'details',
            'cancelledBy',
        ]);

        $pendingCancellationRequest = $transaction
            ->cancellationRequests()
            ->where('status', 'pending')
            ->latest()
            ->first();

        $latestCancellationRequest = $transaction
            ->cancellationRequests()
            ->with(['requester', 'reviewer'])
            ->latest()
            ->first();

        return view('transactions.show', compact(
            'transaction',
            'pendingCancellationRequest',
            'latestCancellationRequest'
        ));
    }

    public function requestCancellation(
        Request $request,
        Transaction $transaction
    ): RedirectResponse {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'reason.required' => 'Alasan pembatalan wajib diisi.',
            'reason.min' => 'Alasan pembatalan minimal 5 karakter.',
        ]);

        DB::transaction(function () use ($transaction, $validated) {

            // Kunci transaksi agar tidak terjadi pengajuan bersamaan.
            $lockedTransaction = Transaction::whereKey($transaction->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Pastikan transaksi masih selesai.
            if ($lockedTransaction->status !== 'completed') {
                throw ValidationException::withMessages([
                    'transaction' => 'Transaksi ini tidak dapat diajukan pembatalannya.',
                ]);
            }

            // Cegah pengajuan ganda yang masih menunggu.
            $existingRequest = CancellationRequest::where(
                'transaction_id',
                $lockedTransaction->id
            )
                ->where('status', 'pending')
                ->exists();

            if ($existingRequest) {
                throw ValidationException::withMessages([
                    'reason' => 'Pengajuan pembatalan untuk transaksi ini masih menunggu persetujuan Owner.',
                ]);
            }

            // Simpan pengajuan pembatalan.
            CancellationRequest::create([
                'transaction_id' => $lockedTransaction->id,
                'requested_by' => auth()->id(),
                'reason' => $validated['reason'],
                'status' => 'pending',
            ]);
        }, 3);

        return redirect()
            ->route('transactions.show', $transaction)
            ->with(
                'success',
                'Pengajuan pembatalan berhasil dikirim dan sedang menunggu persetujuan Owner.'
            );
    }

    public function cancel(
        Request $request,
        Transaction $transaction
    ): RedirectResponse {
        $validated = $request->validate([
            'cancellation_reason' => [
                'required',
                'string',
                'min:5',
                'max:1000',
            ],
        ], [
            'cancellation_reason.required' => 'Alasan pembatalan wajib diisi.',
            'cancellation_reason.min' => 'Alasan pembatalan minimal 5 karakter.',
        ]);

        DB::transaction(function () use ($transaction, $validated) {

            // Kunci transaksi agar tidak dibatalkan secara bersamaan.
            $transaction = Transaction::whereKey($transaction->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Pastikan transaksi belum pernah dibatalkan.
            if ($transaction->status !== 'completed') {
                throw ValidationException::withMessages([
                    'transaction' => 'Transaksi ini sudah dibatalkan atau tidak dapat dibatalkan.',
                ]);
            }

            $details = $transaction->details;

            // Kunci semua produk berdasarkan urutan ID.
            $products = Product::whereIn(
                'id',
                $details->pluck('product_id')->unique()
            )
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            // Kembalikan stok setiap produk.
            foreach ($details as $detail) {
                $product = $products->get($detail->product_id);

                if (!$product) {
                    throw ValidationException::withMessages([
                        'transaction' => "Produk {$detail->product_name} tidak ditemukan.",
                    ]);
                }

                $quantity = (int) $detail->quantity;

                $stockBefore = (int) $product->stock;
                $stockAfter = $stockBefore + $quantity;

                $product->update([
                    'stock' => $stockAfter,
                ]);

                // Catat pengembalian stok.
                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => auth()->id(),
                    'transaction_id' => $transaction->id,
                    'movement_type' => 'incoming',
                    'quantity' => $quantity,
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                    'notes' => 'Pengembalian stok akibat pembatalan transaksi '
                        . $transaction->invoice_number
                        . '. Alasan: '
                        . $validated['cancellation_reason'],
                ]);
            }

            // Simpan informasi pembatalan.
            $transaction->status = 'cancelled';
            $transaction->cancelled_at = now();
            $transaction->cancelled_by = auth()->id();
            $transaction->cancellation_reason = $validated['cancellation_reason'];

            $transaction->save();
        }, 3);

        return redirect()
            ->route('transactions.show', $transaction)
            ->with('success', 'Transaksi berhasil dibatalkan dan stok telah dikembalikan.');
    }
}
