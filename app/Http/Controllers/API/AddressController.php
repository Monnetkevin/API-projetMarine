<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;

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
            $address = Address::create(array_merge($request->all(), ['user_id' => Auth::user()->id] ));
            return response()->json([
                'code' => 201,
                'status' => 'success',
                'data' => $address,
                'message'=> 'Ajout de l\'adresse avec succès'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'Erreur dans l\'ajout de l\'adresse',
                'error' => $e
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Address $address)
    {
        try {
            return response()->json([
                'code' => 200,
                'status' => 'success',
                'data' => $address,
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
    public function update(Request $request, Address $address)
    {
        try {
            $request->validate([
                'address_name' => 'required|string|min:3|max:50',
                'address' => 'required|string|min:3|max:100',
                'postal_code' => 'required|string|size:5',
                'city' => 'required|string|min:3|max:50',
                'country' => 'required|string|min:3|max:50',
            ]);
            $address->update($request->all());

            return response()->json([
                'code' => 201,
                'status' => 'success',
                'data' => $address,
                'message'=> 'Mise à jour de l\'adresse avec succès'
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
    public function destroy(Address $address)
    {
        try {
            $address->delete();
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
