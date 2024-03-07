<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $categories = DB::table('categories')
                ->get()
                ->toArray();
            return response()->json($categories);
        } catch (Exception $e) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'Erreur dans les catégories',
                'error' => $e
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
                    'category_name' => 'required|min:3|max:50|unique:categories'
                ]);
                $category = Category::create($request->all());
                return response()->json([
                    'code' => 201,
                    'status' => 'success',
                    'data' => $category,
                    'message' => 'Ajout de la catégorie avec succès'
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
                'message' => 'Erreur dans l\'ajout de la catégorie',
                'error' => $e
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        try {
            return response()->json([
                'code' => 200,
                'status' => 'success',
                'data' => $category,
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
    public function update(Request $request, Category $category)
    {
        try {
            if (Auth::user()->role_id === 2) {
                $request->validate([
                    'category_name' => 'required|min:3|max:50|unique:categories'
                ]);
                $category->update($request->all());

                return response()->json([
                    'code' => 201,
                    'status' => 'success',
                    'data' => $category,
                    'message' => 'Mise à jour de la catégorie avec succès'
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
    public function destroy(Category $category)
    {
        try {
            if (Auth::user()->role_id === 2) {
                $category->delete();
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
                'error' => $e
            ]);
        }
    }
}
