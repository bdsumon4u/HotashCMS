<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Models\Brand;
use App\Table\Tenant\Admin\BrandTable;
use Inertia\Inertia;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Admin/Brands/Index')->table(BrandTable::class);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreBrandRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBrandRequest $request)
    {
        $brand = Brand::create($request->validated());
        if ($image = $request->file('image')) {
            try {
                $brand->addMedia($image)->toMediaCollection('image');
            } catch (FileDoesNotExist|FileIsTooBig $e) {
                return back()->dangerBanner($e->getMessage());
            }
        }
        session()->flash('flash.banner', 'Brand Has Been Created.');
        return Inertia::location(action([static::class, 'index']));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBrandRequest  $request
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        $brand->update($request->validated());
        if ($image = $request->file('image')) {
            try {
                $brand->addMedia($image)->toMediaCollection('image');
            } catch (FileDoesNotExist|FileIsTooBig $e) {
                return back()->dangerBanner($e->getMessage());
            }
        }
        session()->flash('flash.banner', 'Brand Has Been Updated.');
        return Inertia::location(action([static::class, 'index']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        try {
            $brand->delete();
            session()->flash('flash.banner', 'Brand Has Been Deleted.');
            return Inertia::location(action([static::class, 'index']));
        } catch (\Exception $e) {
            return back()->dangerBanner($e->getMessage());
        }
    }
}
