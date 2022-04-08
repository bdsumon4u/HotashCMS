<?php

namespace App\Table\Tenant\Admin;

use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Hotash\DataTable\InertiaTable;
use Illuminate\Support\Arr;
use Spatie\QueryBuilder\QueryBuilder;

class BrandTable extends InertiaTable
{
    protected string $model = Brand::class;

    protected function buildTable(): void
    {
        $this->addColumns([
            'image' => 'Image',
            'name' => 'Name',
            'slug' => 'Slug',
        ]);
    }

    protected function query(QueryBuilder $builder): QueryBuilder
    {
        return $builder->defaultSort('name')
            ->allowedFields(['id', 'name', 'slug'])
            ->allowedSorts(['id', 'name', 'slug']);
    }

    protected function filters(): array
    {
        return ['id', 'name', 'slug'];
    }

    protected function collection($collection)
    {
        $data = (array)BrandResource::collection($collection)->toResponse(request())->getData();
        return array_merge(Arr::except($data, 'meta'), (array)Arr::get($data, 'meta'));
    }
}
