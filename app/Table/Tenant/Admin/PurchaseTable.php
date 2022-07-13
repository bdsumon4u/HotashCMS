<?php

namespace App\Table\Tenant\Admin;

use App\Http\Resources\PurchaseResource;
use App\Models\Purchase;
use Hotash\DataTable\InertiaTable;
use Illuminate\Support\Arr;
use Spatie\QueryBuilder\QueryBuilder;

class PurchaseTable extends InertiaTable
{
    protected string $model = Purchase::class;

    protected function with()
    {
        return [
            'branch' => function ($query) {
                $query->select(['id', 'name']);
            },
            'supplier' => function ($query) {
                $query->select(['id', 'name']);
            },
        ];
    }

    protected function buildTable(): void
    {
        $this->addColumns([
            'id' => 'ID',
            'purchased_at' => 'Date',
            'supplier' => 'Supplier',
            'branch' => 'Branch',
            'status' => 'Status',
            'total' => 'Total',
            'paid' => 'Paid',
            'due' => 'Due',
            'payment_status' => 'Payment',
        ]);
    }

    protected function query(QueryBuilder $builder): QueryBuilder
    {
        return $builder->defaultSort('status')
            ->with($this->with())
            ->allowedFields(['status'])
            ->allowedSorts(['status'])
            ->orderBy('status');
    }

    protected function filters(): array
    {
        return ['id', 'status'];
    }

    protected function collection($collection)
    {
        $data = (array)PurchaseResource::collection($collection)->toResponse(request())->getData();
        return array_merge(Arr::except($data, 'meta'), (array)Arr::get($data, 'meta'));
    }
}
