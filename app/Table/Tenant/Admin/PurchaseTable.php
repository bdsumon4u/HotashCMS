<?php

namespace App\Table\Tenant\Admin;

use App\Http\Resources\PurchaseResource;
use App\Models\Branch;
use Hotash\DataTable\InertiaTable;
use Illuminate\Support\Arr;
use Spatie\QueryBuilder\QueryBuilder;

class PurchaseTable extends InertiaTable
{
    protected string $model = Branch::class;

    protected function buildTable(): void
    {
        $this->addColumns([
            'name' => 'Name',
            'address' => 'Address',
        ]);
    }

    protected function query(QueryBuilder $builder): QueryBuilder
    {
        return $builder->defaultSort('name')
            ->with(['supplier', 'branch', 'products'])
            ->allowedFields(['name', 'address'])
            ->allowedSorts(['name', 'address'])
            ->orderBy('name');
    }

    protected function filters(): array
    {
        return ['name', 'address'];
    }

    protected function collection($collection)
    {
        $data = (array)PurchaseResource::collection($collection)->toResponse(request())->getData();
        return array_merge(Arr::except($data, 'meta'), (array)Arr::get($data, 'meta'));
    }
}
