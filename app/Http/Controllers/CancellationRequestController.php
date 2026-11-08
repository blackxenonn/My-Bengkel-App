<?php

namespace App\Http\Controllers;

use App\Models\CancellationRequest;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CancellationRequestController extends Controller
{
    /**
     * Menampilkan daftar pengajuan pembatalan transaksi.
     */
    public function index(Request $request): View
    {
        $search = trim($request->input('search', ''));

        $cancellationRequests = CancellationRequest::with([
            'transaction',
            'requester',
            'reviewer',
        ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    // Cari berdasarkan nomor invoice.
                    $query->whereHas('transaction', function ($transactionQuery) use ($search) {
                        $transactionQuery->where(
                            'invoice_number',
                            'like',
                            "%{$search}%"
                        );
                    })

                    // Cari berdasarkan nama Kasir.
                    ->orWhereHas('requester', function ($requesterQuery) use ($search) {
                        $requesterQuery->where(
                            'name',
                            'like',
                            "%{$search}%"
                        );
                    })

                    // Cari berdasarkan alasan pengajuan.
                    ->orWhere('reason', 'like', "%{$search}%")

                    // Cari berdasarkan status.
                    ->orWhere('status', 'like', "%{$search}%");

                    // Mendukung pencarian menggunakan status bahasa Indonesia.
                    $statusKeywords = [
                        'menunggu' => 'pending',
                        'disetujui' => 'approved',
                        'ditolak' => 'rejected',
                    ];

                    foreach ($statusKeywords as $keyword => $status) {
                        if (stripos($keyword, $search) !== false ||
                            stripos($search, $keyword) !== false) {
                            $query->orWhere('status', $status);
                        }
                    }
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('cancellation-requests.index', compact(
            'cancellationRequests',
            'search'
        ));
    }

    /**
     * Menyetujui pengajuan pembatalan transaksi.
     */
    public function approve(
        Request $request,
        CancellationRequest $cancellationRequest
    ): RedirectResponse {
        $validated = $request->validate([
            'review_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use (
            $cancellationRequest,
            $validated
        ) {
            // Kunci pengajuan agar tidak diproses dua kali.
            $lockedRequest = CancellationRequest::whereKey(
                $cancellationRequest->id
            )
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedRequest->status !== 'pending') {
                throw ValidationException::withMessages([
                    'request' => 'Pengajuan ini sudah pernah diproses.',
                ]);
            }

            // Kunci transaksi yang diajukan untuk dibatalkan.
            $transaction = Transaction::whereKey(
                $lockedRequest->transaction_id
            )
                ->lockForUpdate()
                ->firstOrFail();

            if ($transaction->status !== 'completed') {
                throw ValidationException::withMessages([
                    'request' => 'Transaksi ini tidak dapat dibatalkan.',
                ]);
            }

            $transaction->load('details');

            // Ambil dan kunci seluruh produk berdasarkan urutan ID.
            $details = $transaction->details;

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
                        'request' => "Produk {$detail->product_name} tidak ditemukan.",
                    ]);
                }

                $quantity = (int) $detail->quantity;
                $stockBefore = (int) $product->stock;
                $stockAfter = $stockBefore + $quantity;

                $product->update([
                    'stock' => $stockAfter,
                ]);

                // Catat pengembalian stok ke riwayat pergerakan stok.
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
                        . $lockedRequest->reason,
                ]);
            }

            // Ubah status transaksi menjadi dibatalkan.
            $transaction->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancelled_by' => auth()->id(),
                'cancellation_reason' => $lockedRequest->reason,
            ]);

            // Simpan hasil persetujuan Owner.
            $lockedRequest->update([
                'status' => 'approved',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'review_notes' => $validated['review_notes'] ?? null,
            ]);
        }, 3);

        return redirect()
            ->route('cancellation-requests.index')
            ->with(
                'success',
                'Pengajuan pembatalan berhasil disetujui. Stok telah dikembalikan.'
            );
    }

    /**
     * Menolak pengajuan pembatalan transaksi.
     */
    public function reject(
        Request $request,
        CancellationRequest $cancellationRequest
    ): RedirectResponse {
        $validated = $request->validate([
            'review_notes' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'review_notes.required' => 'Alasan penolakan wajib diisi.',
            'review_notes.min' => 'Alasan penolakan minimal 5 karakter.',
        ]);

        DB::transaction(function () use (
            $cancellationRequest,
            $validated
        ) {
            // Kunci pengajuan agar tidak diproses dua kali.
            $lockedRequest = CancellationRequest::whereKey(
                $cancellationRequest->id
            )
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedRequest->status !== 'pending') {
                throw ValidationException::withMessages([
                    'request' => 'Pengajuan ini sudah pernah diproses.',
                ]);
            }

            // Pastikan transaksi masih berstatus selesai.
            $transaction = Transaction::whereKey(
                $lockedRequest->transaction_id
            )
                ->lockForUpdate()
                ->firstOrFail();

            if ($transaction->status !== 'completed') {
                throw ValidationException::withMessages([
                    'request' => 'Transaksi ini sudah tidak berstatus selesai.',
                ]);
            }

            // Simpan hasil penolakan Owner.
            $lockedRequest->update([
                'status' => 'rejected',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'review_notes' => $validated['review_notes'],
            ]);
        }, 3);

        return redirect()
            ->route('cancellation-requests.index')
            ->with(
                'success',
                'Pengajuan pembatalan berhasil ditolak. Stok tidak berubah.'
            );
    }
}
