<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $comment = Comment::with(['users'])->get();
            return response()->json([
                'code' => 200,
                'status' => 'success',
                'data' => $comment,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' =>'Erreur dans la liste des commentaires',
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
                'comment_content' => 'required|min:10',
                'event_id' => 'nullable',
                'product_id' => 'nullable',
            ]);

            $comment = Comment::create(array_merge($request->all(), ['user_id'=> Auth::user()->id]));

            return response()->json([
                'code' => 201,
                'status' => 'success',
                'data' => $comment,
                'message' => 'Ajout de votre commentaire',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'Erreur dans l\'ajout du commentaire',
                'error' => $e,
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        try {
            return response()->json([
                'code' => 200,
                'status' => 'success',
                'data' => $comment,
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
    public function update(Request $request, Comment $comment)
    {
        try {
             if(Auth::user()->id === $comment->user_id) {
                $request->validate([
                    'comment_content' =>'required',
                    'event_id' => 'nullable',
                    'product_id' => 'nullable',
                ]);
                $comment->update($request->all());
                return response()->json([
                    'code'=> 201,
                    'status' => 'success',
                    'data' => $comment,
                    'message' => 'Mise à jour du commentaire avec succès'
                ]);
             } else {
                    return response()->json([
                        'code' => 401,
                        'status' => 'error',
                        'message' => 'Pas autorisé',
                    ]);
                }
            } catch(Exception $e) {
                return response()->json([
                    'code' => 404,
                    'status' => 'error',
                    'message' => 'Erreur dans la suppression',
                    'error' => $e,
                ]);
            }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        try {
            if(Auth::user()->id === $comment->user_id || Auth::user()->role_id === 2) {
                $comment->delete();
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
        } catch(Exception $e) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'Erreur dans la suppression',
                'error' => $e,
            ]);
        }
    }
}
