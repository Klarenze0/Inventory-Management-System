<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStockMovementRequest;
use App\Models\Product;
use App\Models\StockMovement;
use App\Services\StockService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StockMovementController extends Controller
{
    public function __construct(
        private readonly StockService $stockService
    ){}

    // show all stock movements
    public function index(): Response
    {
        $movements = StockMovement::with(['product', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return Inertia::render('StockMovements/Index', [
            'movements' => $movements,
        ]);
    }

    //show form to record movement
    public function create(): Response
    {
        $products = Product::orderBy('name')->get(['id', 'name', 'sku', 'stock_quantity']);

        return Inertia::render('StockMovements/Create', [
            'products'  =>  $products,
        ]);
    }

    // record stock movement
    public function store(StoreStockMovementRequest $request): RedirectResponse
    {
        $product = Product::findOrFail($request->product_id);

        try {
            $this->stockService->recordMovement(
                product:    $product,
                type:       $request->type,
                quantity:   $request->quantity,
                reason:     $request->reason,
            );

            return redirect()
                ->route('stock-movements.index')
                ->with('success', 'Stock movement recorded successfully');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    // show single movement details
    public function show(StockMovement $stockMovement): Response
    {
        $stockMovement->load(['product', 'user']);

        return Inertia::render('StockMovements/Show', [
            'movement'  => $stockMovement,
        ]);
    }
}
