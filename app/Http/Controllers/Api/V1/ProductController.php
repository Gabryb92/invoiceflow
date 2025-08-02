<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ProductResource::collection(Product::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:products,name",
            "default_vat_rate" => "required|numeric|min:0",
            "default_unit_price" => "required|numeric|min:0"
        ]);

        $product = Product::create($validated);

        return (new ProductResource($product)->response()->setStatusCode(201));
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            "name" => "required|unique:products,name," . $product->id,
            "default_vat_rate" => "required|numeric|min:0",
            "default_unit_price" => "required|numeric|min:0"
        ]);

        $product->update($validated);

        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->noContent();
    }

    public function forceDelete(Product $product){
        $product->forceDelete();
        return response()->noContent();

    }

    public function restore(Product $product){
        $product->restore();

        return response()->json(["message" => "Product correctly restored"]);

    }
}
