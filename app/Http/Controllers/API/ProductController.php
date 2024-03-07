<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $products = Product::with(['images'])
                ->get()
                ->toArray();

            // $products = DB::table('products')
            //     ->join('images', 'products.id', '=', 'images.product_id')
            //     ->select('products.*', 'images.image_name')
            //     ->get();

            return response()->json([
                'code' => 200,
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
            if (Auth::user()->role_id === 2) {
                $request->validate([
                    'product_name' => 'required|string|min:3|max:50|unique:products',
                    'product_content' => 'required',
                    'price' => 'required|integer',
                    'quantity' => 'required|integer',
                    'category_id' => 'required',
                ]);
                $product = Product::create($request->all());

                return response()->json([
                    'code' => 201,
                    'status' => 'success',
                    'data' => $product,
                    'message' => 'Ajout du produit avec succès '
                ]);
            } else {
                return response()->json([
                    'code' => 401,
                    'status' => 'error',
                    'message' => 'Pas autorisé',
                ]);
            }
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
            if (Auth::user()->role_id === 2) {
                $request->validate([
                    'product_name' => 'required|string|min:3|max:50|unique:products',
                    'product_content' => 'required',
                    'price' => 'required|integer',
                    'quantity' => 'required|integer',
                    'category_id' => 'required',
                ]);
                $product->update($request->all());

                return response()->json([
                    'code' => 201,
                    'status' => 'success',
                    'data' => $product,
                    'message' => 'Mise à jour du produit avec succès'
                ]);
            } else {
                return response()->json([
                    'code' => 401,
                    'status' => 'error',
                    'message' => 'Pas autorisé',
                ]);
            }
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
