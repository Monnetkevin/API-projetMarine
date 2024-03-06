<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       try {
        $request->validate([
            'image_name' => 'required|image|mimes:jpeg,png,jpg,svg',
            'event_id' => 'nullable',
            'product_id' => 'nullable'
        ]);

        $filename = "";
        if($request->hasFile('image_name')) {
            $filenameWithExt = $request->file('image_name')->getClientOriginalName();
            $filenameWithoutExt = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('image_name')->getClientOriginalExtension();
            $filename = $filenameWithoutExt. '_'. time(). '.'.$extension;
            $request->file('image_name')->storeAs('public/uploads', $filename);
        }
        $image = Image::create(array_merge($request->all(), ['image_name' => $filename]));

        return response()->json([
            'code' => 201,
            'status' => 'success',
            'data' => $image,
            'message' => 'Image ajoutée avec succès',
        ]);

       } catch (Exception $e) {
        return response()->json([
            'code' => 404,
            'status' =>'error',
            'message' => 'Erreur dans l\'ajout de l\'image',
            'erroe' => $e,
        ]);
       }
    }

    /**
     * Display the specified resource.
     */
    public function show(Image $image)
    {
        try {
            return response()->json([
                'code' => 200,
                'status' => 'success',
                'data' => $image,
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
     * Remove the specified resource from storage.
     */
    public function destroy(Image $image)
    {
        try {
            $fileLink = 'public/uploads/' . $image->image_name;
            Storage::delete($fileLink);
            $image->delete();

            return response()->json([
                'code' => 201,
                'status' => 'success',
                'message' => 'Suppression réussite',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 404,
                'status' => 'succes',
                'message' => 'Echec de la suppression',
                'error' => $e,
            ]);
        }
    }
}
