<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Table\Tenant\Admin\CategoryTable;
use Inertia\Inertia;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Admin/Categories/Index')->table(CategoryTable::class);
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
     * @param  \App\Http\Requests\StoreCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());
        if ($image = $request->file('image')) {
            try {
                $category->addMedia($image)->toMediaCollection('image');
            } catch (FileDoesNotExist|FileIsTooBig $e) {
                return back()->dangerBanner($e->getMessage());
            }
        }
        session()->flash('flash.banner', 'Category Has Been Created.');
        return Inertia::location(action([static::class, 'index']));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCategoryRequest  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        if ($image = $request->file('image')) {
            try {
                $category->addMedia($image)->toMediaCollection('image');
            } catch (FileDoesNotExist|FileIsTooBig $e) {
                return back()->dangerBanner($e->getMessage());
            }
        }
        session()->flash('flash.banner', 'Category Has Been Updated.');
        return Inertia::location(action([static::class, 'index']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();
            session()->flash('flash.banner', 'Category Has Been Deleted.');
            return Inertia::location(action([static::class, 'index']));
        } catch (\Exception $e) {
            return back()->dangerBanner($e->getMessage());
        }
    }
}
