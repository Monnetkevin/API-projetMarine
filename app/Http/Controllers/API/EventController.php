<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $event = Event::with(['images'])->get();

            return response()->json([
                'code'=> 200,
                'status' => 'success',
                'data' => $event,
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
            'event_name' => 'required|string|min:3|max:100',
            'event_content' => 'required',
            'start_date' =>'required|date',
            'end_date' => 'required|date',
        ]);
        $event = Event::create($request->all());
        return response()->json([
            'code' => 201,
            'status' => 'success',
            'data' => $event,
            'message' => 'Ajout de l\'événement avec succès'
        ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'Erreur dans l\'ajout de l\'événement',
                'error' => $e,
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        try {
            return response()->json([
                'code' => 200,
                'status' => 'success',
                'data' => $event,
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
    public function update(Request $request, Event $event)
    {
        try {
            $request->validate([
                'event_name' => 'required|string|min:3|max:100',
                'event_content' => 'required',
                'start_date' =>'required|date',
                'end_date' => 'required|date',
            ]);
            $event->update($request->all());

            return response()->json([
                'code' => 201,
                'status' => 'success',
                'data' => $event,
                'message'=> 'Mise à jour de l\'événement avec succès'
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
    public function destroy(Event $event)
    {
        try {
            $category->delete();
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