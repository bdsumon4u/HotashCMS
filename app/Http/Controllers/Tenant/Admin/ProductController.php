<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Table\Tenant\Admin\ProductTable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Admin/Products/Index')->table(ProductTable::class);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Admin/Products/Editor', [
            'brands' => $this->selectable(Brand::class),
            'categories' => $this->selectable(Category::class),
            'attributes' => Attribute::query()->select('id', 'group', 'name', 'values')->get()->groupBy('group')->mapWithKeys(function (Collection $items, $group) {
                return [$group => ['label' => $group, 'options' => $items->transform(fn ($item) => Arr::except($item, 'group'))->toArray()]];
            })->values(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $product = Product::create($data = $request->validated());

        $variations = collect(data_get($data, 'variations'))
            ->map(function ($variation) use ($product) {
                return [
                    'product_id' => $product->getKey(),
                    'name' => $variation['name'],
                    'data' => json_encode($variation),
                ];
            })->toArray();

        $product->variations()->upsert($variations, ['name']);

        return redirect()->action([static::class, 'index'])->banner('Product Has Been Created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return Inertia::render('Admin/Products/Editor', [
            'product' => new ProductResource($product),
            'brands' => $this->selectable(Brand::class),
            'categories' => $this->selectable(Category::class),
            'attributes' => Attribute::query()->select('id', 'group', 'name', 'values')->get()->groupBy('group')->mapWithKeys(function (Collection $items, $group) {
                return [$group => ['label' => $group, 'options' => $items->transform(fn ($item) => Arr::except($item, 'group'))->toArray()]];
            })->values(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($data = $request->validated());

        $variations = collect(data_get($data, 'variations'))
            ->map(function ($variation) use ($product) {
                return [
                    'product_id' => $product->getKey(),
                    'name' => $variation['name'],
                    'data' => json_encode($variation),
                ];
            })->toArray();

        $product->variations()->upsert($variations, ['name']);

        return redirect()->action([static::class, 'index'])->banner('Product Has Been Updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }

    /**
     * Selectable brand or categories
     *
     * @param string $model
     * @return array
     */
    private function selectable(string $model)
    {
        return $model::select('id', 'name')
            ->orderBy('name')
            ->get()
            ->toArray();
    }
}
