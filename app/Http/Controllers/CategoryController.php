<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\ActivityLog;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    // show all category
    public function index(): Response
    {
        $categories = Category::withCount('products')
            ->orderBy('name')
            ->paginate(10);

        return Inertia::render('Categories/Index', [
            'categories' => $categories,
        ]);
    }

    // show create form
    public function create(): Response
    {
        return Inertia::render('Categories/Create');
    }

    // save new category
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $category = Category::create($request->validated());

        ActivityLog::create([
            'user_id'           => auth()->id(),
            'action'            => 'created_category',
            'description'       => 'Created category: ${category->name}',
            'subject_type'      => 'Category',
            'subject_id'        => $category->id,
        ]);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category created successfully');
    }

    // show single category  with products
    public function show(Category $category): Response
    {
        $category->load('products');

        return Inertia::render('Categories/Show', [
            'category' => $category,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category): Response
    {
        return Inertia::render('Categories/Edit', [
            'category' => $category
        ]);
    }

    // show edit form
    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());

        ActivityLog::create([
            'user_id'           => auth()->id(),
            'action'            => 'updated_category',
            'description'       => 'Updated category: {$category->name}',
            'subject_type'      => 'Category',
            'subject_id'        => $category->id,
        ]);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category updated successfully');
    }

    // delete a category
    public function destroy(Category $category): RedirectResponse
    {
        // check if may existing products sa category na yon
        if ($category->products()->count() > 0) {
            return redirect()
                ->back()
                ->with('error', 'Cannot delete category with existing products');
        }

        $categoryName = $category->name;

        $category->delete();

        ActivityLog::create([
            'user_id'       => auth()->id(),
            'acion'         => 'deleted_category',
            'description'   => 'Deleted category: {$categoryName}',
        ]);    

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category deleted successfully');
    }
}
