<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class StockMovementController extends Controller
{
    public function index(): View
    {
        $movements = StockMovement::with(['product', 'user'])
            ->latest()
            ->paginate(15);

        return view('stock-movements.index', compact('movements'));
    }

    public function create(): View
    {
        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('stock-movements.create', compact('products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'movement_type' => [
                'required',
                Rule::in(['incoming', 'outgoing', 'adjustment']),
            ],
            'quantity' => ['required', 'integer', 'not_in:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if (
            in_array($validated['movement_type'], ['incoming', 'outgoing'])
            && $validated['quantity'] < 1
        ) {
            return back()
                ->withInput()
                ->withErrors(['quantity' => 'Jumlah stok masuk dan keluar harus lebih dari 0.']);
        }

        if (
            in_array($validated['movement_type'], ['outgoing', 'adjustment'])
            && empty(trim($validated['notes'] ?? ''))
        ) {
            return back()
                ->withInput()
                ->withErrors(['notes' => 'Alasan wajib diisi untuk stok keluar dan penyesuaian stok.']);
        }

        try {
            DB::transaction(function () use ($validated) {
                $product = Product::whereKey($validated['product_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                $stockBefore = $product->stock;

                $quantity = match ($validated['movement_type']) {
                    'incoming' => abs($validated['quantity']),
                    'outgoing' => -abs($validated['quantity']),
                    'adjustment' => $validated['quantity'],
                };

                $stockAfter = $stockBefore + $quantity;

                if ($stockAfter < 0) {
                    throw new \RuntimeException(
                        'Stok tidak mencukupi. Stok saat ini: ' . $stockBefore
                    );
                }

                $product->update([
                    'stock' => $stockAfter,
                ]);

                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => auth()->id(),
                    'transaction_id' => null,
                    'movement_type' => $validated['movement_type'],
                    'quantity' => $quantity,
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                    'notes' => $validated['notes'] ?? null,
                ]);
            });
        } catch (\RuntimeException $exception) {
            return back()
                ->withInput()
                ->withErrors(['quantity' => $exception->getMessage()]);
        }

        return redirect()->route('stock-movements.index')
            ->with('success', 'Pergerakan stok berhasil disimpan.');
    }
}