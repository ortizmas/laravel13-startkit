<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Dto\ProductDto;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
      //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $dto = new ProductDTO(
        //     title: $request->input('title', 'Default Title'),
        //     price: $request->input('price', 0.0),
        //     stock: $request->input('stock', 0)
        // );

        $dto = new ProductDTO(
            ...$request->only(['title', 'price', 'stock'])
        );

        $product = $this->createProduct($dto);

        return response()->json($product, 201);
    }

    private function createProduct(ProductDto $dto): Product
    {
        return Product::create($dto->toArray());
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
