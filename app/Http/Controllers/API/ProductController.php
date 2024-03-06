<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $products = Product::with(['images'])->get();
            return response()->json([
                'code'=> 200,
                'status' => 'success',
                'data' => $products,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'Erreur dans la liste des événements',
                'error' => $e,
            ]);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
         $request->validate([
            'product_name' => 'required|string|min:3|max:50|unique:products',
            'product_content' => 'required',
            'price' => 'required|integer',
            'quantity' => 'required|integer',
        ]);
        $product = Product::create($request->all());

        return response()->json([
            'code' => 201,
            'status' => 'success',
            'data' => $product,
            'message' => 'Ajout du produit avec succès '
        ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'Erreur dans l\'ajout',
                'error' => $e
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        try {
            return response()->json([
                'code' => 200,
                'status' => 'success',
                'data' => $product,
            ]);
        } catch (Exception $e) {
            return response()->json([
                 'code' => 404,
                 'status' => 'error',
                 'message' => 'Erreur dans l\'affichage',
                 'error' => $e
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        try {
            $request->validate([
                'product_name' => 'required|string|min:3|max:50|unique:products',
                'product_content' => 'required',
                'price' => 'required|integer',
                'quantity' => 'required|integer',
            ]);
            $product->update($request->all());

            return response()->json([
                'code' => 201,
                'status' => 'success',
                'data' => $product,
                'message'=> 'Mise à jour du produit avec succès'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'Erreur dans la modification',
                'error' => $e
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return response()->json([
                'code' => 201,
                'status' => 'success',
                'message' => 'Suppression réussite'
            ]);
        } catch (Exception $e) {
            return response()->json([
                 'code' => 404,
                 'status' => 'error',
                 'message' => 'Erreur dans la suppression',
                 'error' => $e
            ]);
        }
    }
}