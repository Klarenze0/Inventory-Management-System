<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    // show all products
    public function index(): Response
    {
        $products = Product::with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('Products/Index', [
            'products' => $products,    
        ]);
    }

    // show create form
    public function create(): Response
    {
        $categories = Category::orderBy('name')->get();

        return Inertia::render('Products/Create', [
            'categories' => $categories,
        ]);
    }

    // save the new product
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // handle image if meron
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request
                ->file('image')
                ->store('products', 'public');
        }

        // alisin yung image key kasi hindi column sa database
        unset($validated['image']);

        $product = Product::create($validated);

        // ilog yung activity
        ActivityLog::create([
            'user_id'       => auth()->id(),
            'action'        => 'created_product',
            'description'   => "Created product: {$product->name} (SKU: {$product->sku})",
            'subject_type'  => 'Product',
            'subject_id'    =>  $product->id,
        ]);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product created successfully');
    }

    // show single product
    public function show(Product $product): Response
    {
        $product->load(['category', 'stockMovements.user']);

        return Inertia::render('Products/Show', [
            'product' => $product,
        ]);
    }

    //show edit form
    public function edit(Product $product): Response
    {
        $categories = Category::orderBy('name')->get();

        return Inertia::render('Products/Edit', [
            'product'       => $product,
            'categories'    => $categories,
        ]);
    }

    // save updated product
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $validated = $request->validated();

        // handle image if meron
        if ($request->hasFile('image')) {
            // delete old image if meron
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }

            $validated['image_path'] = $request
                ->file('image')
                ->store('products', 'public');
        }

        unset($validated['image']);

        $product->update($validated);

        ActivityLog::create([
            'user_id'           => auth()->id(),
            'action'            => 'updated_product',
            'description'       => "Updated product: {$product->name} (SKU: {$product->sku})",
            'subject_type'      => 'Product',
            'subject_id'        => $product->id,
        ]);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product updated successfully');
    }

    // destroy product
    public function destroy(Product $product): RedirectResponse
    {
        // delete image if meron
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $productName = $product->name;
        $productSku = $product->sku;

        $product->delete();

        ActivityLog::create([
            'user_id'           => auth()->id(),
            'action'            => 'deleted_product',
            'description'       => "Deleted product: {$productName} (SKU: {$productSku})",
        ]);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted successfully');
    }
}
