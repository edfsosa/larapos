<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PosController extends Controller
{
    public function index()
    {
        return Inertia::render('pos/Index');
    }

    public function products(Request $request)
    {
        $q = $request->get('q');

        $products = Product::active()
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('sku', 'like', "%{$q}%")
                        ->orWhere('barcode', $q);
                });
            })
            ->with('category:id,name,color')
            ->orderBy('name')
            ->limit(50)
            ->get(['id', 'name', 'sku', 'barcode', 'price', 'stock', 'category_id']);

        return response()->json($products);
    }
}
