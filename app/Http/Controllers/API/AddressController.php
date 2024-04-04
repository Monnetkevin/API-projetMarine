<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use App\Models\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'address_name' => 'required|string|min:3|max:50',
                'address' => 'required|string|min:3|max:100',
                'postal_code' => 'required|string|size:5',
                'city' => 'required|string|min:3|max:50',
                'country' => 'required|string|min:3|max:50',
            ]);
            $address = Address::create(array_merge($request->all(), ['user_id' => Auth::user()->id]));
            return response()->json([
                'code' => 201,
                'status' => 'success',
                'data' => $address,
                'message' => 'Ajout de l\'adresse avec succès'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'Erreur dans l\'ajout de l\'adresse',
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        try {

            if (Auth::user()->id === $user->id || Auth::user()->role_id === 2) {

                $adresses = Address::where('user_id', $user->id)->get();

                return response()->json([
                    'code' => 200,
                    'status' => 'success',
                    'data' => $adresses,
                ]);
            } else {
                return response()->json([
                    'code' => 401,
                    'status' => 'error',
                    'message' => 'Pas autorisé'
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'Erreur dans l\'affichage',
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Address $address)
    {
        try {
            if (Auth::user()->id === $address->user_id) {
                $request->validate([
                    'address_name' => 'string|min:3|max:50',
                    'address' => 'string|min:3|max:100',
                    'postal_code' => 'string|size:5',
                    'city' => 'string|min:3|max:50',
                    'country' => 'string|min:3|max:50',
                ]);
                $address->update($request->all());
                return response()->json([
                    'code' => 201,
                    'status' => 'success',
                    'data' => $address,
                    'message' => 'Mise à jour de l\'adresse avec succès'
                ]);
            } else {
                return response()->json([
                    'code' => 401,
                    'status' => 'error',
                    'message' => 'Pas autorisé'
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'Erreur dans la modification',
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Address $address)
    {
        try {
            if (Auth::user()->id === $address->user_id) {
                $address->delete();
                return response()->json([
                    'code' => 201,
                    'status' => 'success',
                    'message' => 'Suppression réussite'
                ]);
            } else {
                return response()->json([
                    'code' => 401,
                    'status' => 'error',
                    'message' => 'Pas autorisé'
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
}
