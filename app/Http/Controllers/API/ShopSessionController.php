<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use App\Models\Product;
use App\Models\ShopSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\ProductShopSession;
use Illuminate\Support\Facades\Auth;

class ShopSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // $shop = ShopSession::with('products')->get();
        // return response()->json([
        //     'data' => $shop,
        // ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        if (Auth::user()) {
            $currentShop = ShopSession::where('user_id', Auth::user()->id)
                ->where('active', true)
                ->exists();

            if (!$currentShop) {
                $shopSession = ShopSession::create([
                    'total' => 0,
                    'user_id' => Auth::user()->id,
                    'active' => 1,
                ]);

                return response()->json([
                    'code' => 201,
                    'status' => 'success',
                    'data' => $shopSession,
                    'message' => 'Création du panier'
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'un panier déjà en cours',
                ]);
            }
        } else {
            return response()->json([
                'code' => 401,
                'status' => 'error',
                'message' => 'Pas autorisé',
            ]);
        }
    }



    /**
     * Display the specified resource.
     */
    public function show($user)
    {
        if (Auth::user()->id == $user) {
            $shop = ShopSession::where('user_id', $user)
                ->where('active', '1')
                ->with('products:id,product_name,price,stripe_id,stripe_price')
                ->first();

            for ($i = 0; $i < $shop->products->count(); $i++) {
                $image = Image::where('product_id', $shop->products[$i]->id)
                    ->select('images.image_name')
                    ->first();
                $shop->products[$i]->image = $image->image_name;
            }

            return response()->json([
                'code' => 200,
                'status' => 'success',

                'data' =>  $shop,

            ]);
        } else {
            return response()->json([
                'code' => 401,
                'status' => 'error',
                'message' => 'Pas autorisé',
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShopSession $shopSession)
    {
        if (Auth::user()) {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                // 'shopSession_id' => 'required|exists:shop_sessions,id',
                'product_quantity' => 'required|integer|min:1',
            ]);
            $shopSession = ShopSession::find($request->shopSession_id);
            $product = Product::find($request->product_id);

            // $quantity = ProductShopSession::find($request->shopSession_id);

            // dd($quantity);

            $shopSession->products()->updateExistingPivot($product->id, ['product_quantity' => $request->product_quantity]);

            $total = round($shopSession->total +  ($product->price * $request->product_quantity), 2);
            $shopSession->update(['total' => $total]);

            return response()->json([
                'code' => 201,
                'status' => 'success',
                'data' => $shopSession,
                'message' => 'Produit retiré du panier avec succès.'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function removeProductShop(Request $request)
    {
        if (Auth::user()) {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'shopSession_id' => 'required|exists:shop_sessions,id',
                'product_quantity' => 'required|integer|min:1',
            ]);
            $shopSession = ShopSession::find($request->shopSession_id);
            $product = Product::find($request->product_id);

            $shopSession->products()->detach($product->id);

            $total = round($shopSession->total - ($product->price * $request->product_quantity), 2);
            $shopSession->update(['total' => $total]);

            return response()->json([
                'code' => 201,
                'status' => 'success',
                'data' => $shopSession,
                'message' => 'Produit retiré du panier avec succès.'
            ]);
        }
    }
    public function addToShop(Request $request)
    {
        try {
            if (Auth::user()) {
                $store = new ShopSessionController();
                $store->store();

                $request->validate([
                    'product_id' => 'required|exists:products,id',
                    'product_quantity' => 'required|integer|min:1'
                ]);

                $shopSession = ShopSession::where('user_id', Auth::user()->id)
                    ->where('active', true)
                    ->first();

                $product = Product::find($request->product_id);
                $shopSession->products()->attach($product->id, ['product_quantity' => $request->product_quantity]);


                $total = round($shopSession->total + ($product->price * $request->product_quantity), 2);
                $shopSession->update(['total' => $total]);


                return response()->json([
                    'code' => 201,
                    'status' => 'success',
                    'data' => $shopSession,
                    'message' => 'Ajout du produit dans votre panier'
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
                'message' => 'Erreur dans le panier',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
