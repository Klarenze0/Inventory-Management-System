<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\ActivityLog;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class SupplierController extends Controller
{
    // show all suppliers
    public function index(): Response
    {
        $suppliers = Supplier::orderBy('company_name')
            ->paginate(10);

        return Inertia::render('Suppliers/Index', [
            'supplier' => $suppliers, 
        ]);
    }

    // show create form
    public function create(): Response
    {
        return Inertia::render('Suppliers/Create');
    }

    // save new supplier
    public function store(StoreSupplierRequest $request): RedirectResponse
    {
        $supplier = Supplier::create($request->validated());

        ActivityLog::create([
            'user_id'           => auth()->id(),
            'action'            => 'created_supplier',
            'description'       => 'Create supplier: {$supplier->company_name}',
            'subject_type'      => 'Supplier',
            'subject_id'        => $supplier->id,
        ]);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier created successfully');
    }

    // show single supplier
    public function show(Supplier $supplier): Response
    {
        return Inertia::render('Supplier/Show', [
            'supplier' => $supplier,
        ]);
    }

    // show edit form
    public function edit(Supplier $supplier): Response
    {
        return Inertia::render('Suppliers/Edit', [
            'supplier' => $supplier,
        ]);
    }

    // save updated supplier
    public function update(UpdateSupplierRequest $request, Supplier $supplier): RedirectResponse
    {
        $supplier->update($request->validated());

        ActivityLog::create([
            'user_id'           => auth()->id(),
            'action'            => 'updated_supplier',
            'description'       => 'Updated supplier: {$supplier->company_name}',
            'subject_type'      => 'Supplier',
            'subject_id'        => $supplier->id,
        ]);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier updated successfully');
    }

    // delete supplier
    public function destroy(Supplier $supplier): RedirectResponse
    {
        $supplierName = $supplier->company_name;

        $supplier->delete();

        ActivityLog::create([
            'user_id'       => auth()->id(),
            'action'        => 'deleted_supplier',
            'description'   => 'Deleted supplier: {$supplierName}',
        ]);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier deleted successfully');
    }
}
