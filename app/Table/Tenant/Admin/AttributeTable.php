<?php

namespace App\Table\Tenant\Admin;

use App\Http\Resources\AttributeResource;
use App\Models\Attribute;
use Hotash\DataTable\InertiaTable;
use Illuminate\Support\Arr;
use Spatie\QueryBuilder\QueryBuilder;

class AttributeTable extends InertiaTable
{
    protected string $model = Attribute::class;

    protected function buildTable(): void
    {
        $this->addColumns([
            'group' => 'Group',
            'name' => 'Name',
            'values' => 'Values',
        ]);
    }

    protected function query(QueryBuilder $builder): QueryBuilder
    {
        return $builder->defaultSort('group')
            ->allowedFields(['id', 'group', 'name', 'values'])
            ->allowedSorts(['id', 'group', 'name', 'values'])
            ->orderBy('name');
    }

    protected function filters(): array
    {
        return ['id', 'group', 'name', 'values'];
    }

    protected function collection($collection)
    {
        $data = (array)AttributeResource::collection($collection)->toResponse(request())->getData();
        return array_merge(Arr::except($data, 'meta'), (array)Arr::get($data, 'meta'));
    }
}
