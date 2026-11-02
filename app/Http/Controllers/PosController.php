<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PosController extends Controller
{
    public function index(): View
    {
        $products = Product::with('category')
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->orderBy('name')
            ->get();

        return view('pos.index', compact('products'));
    }

    public function checkout(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => [
                'required',
                'integer',
                'distinct',
                'exists:products,id',
            ],
            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],
            'paid_amount' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        $transaction = DB::transaction(function () use ($validated) {
            $items = collect($validated['items']);

            // Kunci semua produk selama proses checkout.
            $products = Product::whereIn(
                'id',
                $items->pluck('product_id')
            )
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $total = 0;
            $transactionItems = [];

            // Periksa ulang status dan stok serta hitung harga dari database.
            foreach ($items as $item) {
                $product = $products->get($item['product_id']);

                if (!$product || !$product->is_active) {
                    throw ValidationException::withMessages([
                        'items' => 'Ada produk yang sudah tidak aktif atau tidak tersedia.',
                    ]);
                }

                $quantity = (int) $item['quantity'];

                if ($product->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'items' => "Stok {$product->name} tidak mencukupi. Stok tersedia: {$product->stock}.",
                    ]);
                }

                $unitPrice = (int) round((float) $product->selling_price);
                $subtotal = $unitPrice * $quantity;

                $total += $subtotal;

                $transactionItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                ];
            }

            $paidAmount = (int) $validated['paid_amount'];

            if ($paidAmount < $total) {
                throw ValidationException::withMessages([
                    'paid_amount' => 'Uang pembayaran tidak mencukupi.',
                ]);
            }

            $changeAmount = $paidAmount - $total;

            $invoiceNumber = 'TRX-' . now()->format('YmdHis')
                . '-' . Str::upper(Str::random(6));

            $transaction = Transaction::create([
                'invoice_number' => $invoiceNumber,
                'user_id' => auth()->id(),
                'total' => $total,
                'payment_method' => 'cash',
                'paid_amount' => $paidAmount,
                'change_amount' => $changeAmount,
                'status' => 'completed',
            ]);

            foreach ($transactionItems as $item) {
                $product = $item['product'];
                $quantity = $item['quantity'];
                $stockBefore = $product->stock;
                $stockAfter = $stockBefore - $quantity;

                // Simpan detail transaksi menggunakan data produk dari database.
                $transaction->details()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'sku' => $product->sku,
                    'quantity' => $quantity,
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['subtotal'],
                ]);

                // Kurangi stok produk.
                $product->update([
                    'stock' => $stockAfter,
                ]);

                // Catat perubahan stok akibat penjualan.
                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => auth()->id(),
                    'transaction_id' => $transaction->id,
                    'movement_type' => 'sale',
                    'quantity' => -$quantity,
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                    'notes' => 'Penjualan POS - ' . $invoiceNumber,
                ]);
            }

            return $transaction;
        }, 3);

        return response()->json([
            'message' => 'Transaksi berhasil disimpan.',
            'invoice_number' => $transaction->invoice_number,
            'total' => (float) $transaction->total,
            'paid_amount' => (float) $transaction->paid_amount,
            'change_amount' => (float) $transaction->change_amount,
        ], 201);
    }
}