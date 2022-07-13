<?php

namespace App\Table\Tenant\Admin;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Hotash\DataTable\InertiaTable;
use Illuminate\Support\Arr;
use Spatie\QueryBuilder\QueryBuilder;

class ProductTable extends InertiaTable
{
    protected string $model = Product::class;

    protected function buildTable(): void
    {
        $this->addColumns([
            'name' => 'Name',
            'sku' => 'SKU',
            'barcode' => 'Barcode',
            'regular_price' => 'Regular Price',
            'sale_price' => 'Sale Price',
        ]);
    }

    protected function query(QueryBuilder $builder): QueryBuilder
    {
        return $builder->defaultSort('name')
            ->with('variations')
            ->allowedFields(['id', 'sku', 'name', 'barcode'])
            ->allowedSorts(['id', 'sku', 'name', 'barcode'])
            ->orderBy('name');
    }

    protected function filters(): array
    {
        return ['id', 'sku', 'name', 'barcode'];
    }

    protected function collection($collection)
    {
        $data = (array)ProductResource::collection($collection)->toResponse(request())->getData();
        return array_merge(Arr::except($data, 'meta'), (array)Arr::get($data, 'meta'));
    }
}
