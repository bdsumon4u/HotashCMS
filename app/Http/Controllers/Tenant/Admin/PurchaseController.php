<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Enums\PurchaseStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Unit;
use App\Table\Tenant\Admin\PurchaseTable;
use Hotash\Ignorable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        dd(Purchase::with('products')->get());
        return Inertia::render('Admin/Purchases/Index')->table(PurchaseTable::class);
    }

    public function products(Request $request)
    {
        $products = Product::search($request->get('query'))
            ->query(fn ($query) => $query->doesntHave('variations')
                ->with([
                    'parent' => fn ($query) => $query->with([
                        'media' => fn ($query) => $query->take(1),
                    ])->select('id', 'name'),
                    'media' => fn ($query) => $query->take(1),
                ])
            )->take(5)->get();

        return response()->json($products->transform(function ($model) {
            $data = $model->toArray();

            $data['media'] = $model->media->first()?->preview_url;
            if ($model->parent) {
                $data['name'] = $model->parent->name . ' [ ' . $data['name'] . ' ]';
                if (! $data['media']) {
                    $data['media'] = $model->parent->media->first()?->preview_url;
                }
            }

            if (!$model->media) {
                return $model;
            }

            return $data;
        })->toArray());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $statuses = collect(PurchaseStatus::cases())
            ->map(fn ($case) => [
                'label' => $case->name,
                'value' => $case->value
            ])->toArray();

        return Inertia::render('Admin/Purchases/Create', [
            'statuses' => $statuses,
            'units' => Unit::all()->toArray(),
            'branches' => $this->selectable(Branch::class),
            'suppliers' => $this->selectable(Supplier::class),
            'products' => Product::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePurchaseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePurchaseRequest $request)
    {
//        $request->dd();
        $data = $request->validated();

        DB::transaction(function () use (&$data) {
            /** @var Purchase $purchase */
            $purchase = Purchase::create($data);
            foreach ($data['products'] as $id => $product) {
                $data['products'][$id]['net_price'] = 0;
            }
            $purchase->products()->attach($data['products']);
        });

        return back()->banner('Product Purchase Created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePurchaseRequest  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePurchaseRequest $request, Purchase $purchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
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
        return $model::select('id', 'name', 'phone', 'email')
            ->orderBy('name')
            ->get()
            ->toArray();
    }
}
