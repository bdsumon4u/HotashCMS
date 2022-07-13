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
use App\Models\Variation;
use App\Table\Tenant\Admin\ProductTable;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

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
        /** @var Brand $brand */
        $brand = Brand::query()->findOrFail($request->get('brand_id'));

        /** @var Product $product */
        $product = $brand->products()->create($data = $request->validated());

        try {
            $this->syncRelations($product, $data);
        } catch (FileDoesNotExist|FileIsTooBig|FileCannotBeAdded $e) {
        }

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
        $product->load(['media', 'variations.media']);
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

        try {
            $this->syncRelations($product, $data);
        } catch (FileDoesNotExist|FileIsTooBig|FileCannotBeAdded $e) {
        }

        return redirect()->action([static::class, 'index'])->banner('Product Has Been Updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @return Response
     */
    public function destroy(Product $product): Response
    {
        //
    }

    /**
     * Selectable brand or categories
     *
     * @param string $model
     * @return array
     */
    private function selectable(string $model): array
    {
        return $model::select('id', 'name')
            ->orderBy('name')
            ->get()
            ->toArray();
    }

    /**
     * @throws FileCannotBeAdded
     * @throws FileIsTooBig
     * @throws FileDoesNotExist
     */
    private function syncRelations(Product $product, array $data)
    {
        foreach ($data['media'] as $item) {
            if (data_get($item, 'id')) continue;
            $product->addMediaFromUrl($item['src'])
                ->toMediaCollection('default', 'imagekit');
        }

        DB::beginTransaction();
        collect(data_get($data, 'variations'))
            ->each(function ($data, $name) use (&$product) {
                /** @var Product $variation */
                $variation = $product->variations()
                    ->updateOrCreate(
                        compact('name'),
                        array_merge(Arr::except($data, 'media'), [
                            'slug' => Str::slug($name),
                        ])
                    );

                foreach (data_get($data, 'media', []) as $item) {
                    if (data_get($item, 'id')) continue;
                    $variation->addMediaFromUrl($item['src'])
                        ->toMediaCollection('default', 'imagekit');
                }
            });
        DB::commit();
    }
}
