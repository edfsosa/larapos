<?php

namespace App\Actions;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RegisterSaleAction
{
    /**
     * @param array $data ['items' => [['product_id', 'quantity']], 'payment_method', 'amount_received']
     */
    public function execute(array $data, int $userId): Sale
    {
        return DB::transaction(function () use ($data, $userId) {
            $items = collect($data['items']);

            if ($items->isEmpty()) {
                throw ValidationException::withMessages([
                    'items' => 'El carrito está vacío.',
                ]);
            }

            $subtotal = 0;
            $lines = [];

            foreach ($items as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                if (! $product->active) {
                    throw ValidationException::withMessages([
                        'items' => "El producto {$product->name} no está disponible.",
                    ]);
                }

                if ($product->stock < $item['quantity']) {
                    throw ValidationException::withMessages([
                        'items' => "Stock insuficiente para {$product->name}. Disponible: {$product->stock}.",
                    ]);
                }

                $lineSubtotal = (int) $product->price * (int) $item['quantity'];
                $subtotal += $lineSubtotal;

                $lines[] = [
                    'product' => $product,
                    'quantity' => (int) $item['quantity'],
                    'unit_price' => (int) $product->price,
                    'subtotal' => $lineSubtotal,
                ];
            }

            // IVA 10% incluido en el precio (Paraguay)
            $tax = (int) round($subtotal - ($subtotal / 1.10));
            $total = (int) $subtotal;

            $change = null;
            if ($data['payment_method'] === 'cash') {
                $received = (int) ($data['amount_received'] ?? 0);
                if ($received < $total) {
                    throw ValidationException::withMessages([
                        'amount_received' => 'El monto recibido es menor al total.',
                    ]);
                }
                $change = $received - $total;
            }

            $sale = Sale::create([
                'user_id' => $userId,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                'payment_method' => $data['payment_method'],
                'amount_received' => isset($data['amount_received']) ? (int) $data['amount_received'] : null,
                'change' => $change,
                'reference' => Sale::generateReference(),
            ]);

            foreach ($lines as $line) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $line['product']->id,
                    'quantity' => $line['quantity'],
                    'unit_price' => $line['unit_price'],
                    'subtotal' => $line['subtotal'],
                ]);

                $line['product']->decrement('stock', $line['quantity']);
            }

            return $sale->load('items.product', 'cashier');
        });
    }
}
