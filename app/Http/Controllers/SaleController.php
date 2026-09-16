<?php

namespace App\Http\Controllers;

use App\Actions\RegisterSaleAction;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function store(Request $request, RegisterSaleAction $action)
    {
        $data = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'payment_method' => ['required', 'in:cash,card,mixed'],
            'amount_received' => ['nullable', 'integer', 'min:0'],
        ]);

        $sale = $action->execute($data, $request->user()->id);

        return response()->json($sale, 201);
    }
}
