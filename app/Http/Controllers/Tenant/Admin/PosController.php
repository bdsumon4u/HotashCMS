<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PosController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $products = Product::with('variations')->get()
            ->map(function (Product $product) {
                if ($product->variations->isEmpty()) {
                    return $product;
                }
                return $product->variations->map(function (Product $variation) use (&$product) {
                    return $variation->forceFill([
                        'name' => $product->name . ' ['.$variation->name.']',
                    ]);
                });
            })->flatten();
        return Inertia::render('Admin/Pos', compact('products'));
    }
}
