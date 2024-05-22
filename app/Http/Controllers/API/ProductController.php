<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;
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

            return response()->json([
                'code' => 200,
                'status' => 'success',
                'data' => $products,

            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 400,
                'status' => 'error',
                'message' => 'Erreur dans la liste des événements',
                'error' => $e->getMessage(),
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

                $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
                $stripes = $stripe->products->create([
                    'name' => $request->product_name,
                    'tax_code' => 'txcd_35010000',
                    'default_price_data' =>
                    [
                        'currency' => 'EUR',
                        'unit_amount' => $request->price * 100,
                    ],
                ]);

                $product = Product::create(array_merge($request->all(), ['stripe_id' => $stripes->id, 'stripe_price' => $stripes->default_price]));

                return response()->json([
                    'code' => 201,
                    'status' => 'success',
                    'data' => $product,
                    'stripe' => $stripes,
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
                'code' => 400,
                'status' => 'error',
                'message' => 'Erreur dans l\'ajout',
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        try {

            $images = Image::where('product_id', $product->id)
                ->get();

            return response()->json([
                'code' => 200,
                'status' => 'success',
                'data' => $product,
                'image' => $images,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 400,
                'status' => 'error',
                'message' => 'Erreur dans l\'affichage',
                'error' => $e->getMessage()
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
                'code' => 400,
                'status' => 'error',
                'message' => 'Erreur dans la modification',
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            if (Auth::user()->role_id === 2) {
                $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
                $stripe->products->update($product->stripe_id, ['active' => false]);

                $product->delete();

                return response()->json([
                    'code' => 201,
                    'status' => 'success',
                    'message' => 'Suppression réussite'
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
                'message' => 'Erreur dans la suppression',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function lastProduct()
    {
        try {
            $lastProducts = Product::with(['images'])->latest()->take(3)->get();

            return response()->json([
                'code' => 200,
                'status' => 'success',
                'data' => $lastProducts,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'Erreur dans la liste des événements',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
