<?php

namespace App\Table\Tenant\Admin;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Hotash\DataTable\InertiaTable;
use Illuminate\Support\Arr;
use Spatie\QueryBuilder\QueryBuilder;

class CategoryTable extends InertiaTable
{
    protected string $model = Category::class;

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
        $data = (array)CategoryResource::collection($collection)->toResponse(request())->getData();
        return array_merge(Arr::except($data, 'meta'), (array)Arr::get($data, 'meta'));
    }
}
