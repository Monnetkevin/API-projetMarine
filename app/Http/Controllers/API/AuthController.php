<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
// use Laravolt\Avatar\Avatar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct(User $user)
    {
        $this->user = $user;
    }
    public function register(Request $request)
    {
        try {
            $request->validate([
                'first_name' => 'required|string|min:2|max:50',
                'last_name' => 'required|string|min:2|max:50',
                'email' => 'required|string|email:rfc,dns|max:100|unique:users',
                'password' => 'required|string|min:6|max:100',
                'phone_number' => 'required',
            ]);
            $user = $this->user::create([
                'first_name' => $request['first_name'],
                'last_name' => $request['last_name'],
                'email' => $request['email'],
                'phone_number' => $request['phone_number'],
                'password' => bcrypt($request['password']),
                'image_name' => 'default_avatar.jpg',

            ]);
            return response()->json([
                'meta' => [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Création avec succès',
                ],
                'data' => [
                    'user' => $user,
                    'access_token' => [
                        'type' => 'Bearer',
                        'expires_in' => auth()->factory()->getTTL() * 7200,
                    ],
                ],
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 400,
                'status' => 'error',
                'message' => 'Erreur lors de l\'enregistrement',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);
            $token = auth()->attempt([
                'email' => $request->email,
                'password' => $request->password,
            ]);
            if ($token) {
                return response()->json([
                    'meta' => [
                        'code' => 200,
                        'status' => 'success',
                        'message' => 'Connexion effectuée !',
                    ],
                    'data' => [
                        'user' => auth()->user(),
                        'access_token' => [
                            'token' => $token,
                            'type' => 'Bearer',
                        ],
                    ],
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Erreur dans la connexion',
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'Erreur dans la connexion',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function logout()
    {
        try {
            $token = JWTAuth::getToken();
            $invalidate = JWTAuth::invalidate($token);
            if ($invalidate) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Déconnexion avec succès',
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pas autorisé'
                ], 401);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'success',
                'message' => 'Erreur dans la déconnexion',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    public function currentUser()
    {
        return response()->json(Auth::user());
    }

    public function update(Request $request, User $user)
    {
        try {
            if (Auth::user()->id === $user->id) {
                $request->validate([
                    'first_name' => 'min:2|max:50',
                    'last_name' => 'min:2|max:50',
                    'email' => 'email:rfc,dns|max:100',
                    'phone_number' => 'size:10',
                    'image_name' => 'image|mimes:jpeg,png,jpg,svg',
                ]);

                $filename = "";
                if ($request->hasFile('image_name')) {
                    $filenameWithExt = $request->file('image_name')->getClientOriginalName();
                    $filenameWithoutExt = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension = $request->file('image_name')->getClientOriginalExtension();
                    $filename = $filenameWithoutExt . '_' . time() . '.' . $extension;
                    $request->file('image_name')->storeAs('public/uploads', $filename);
                }


                $user->update(array_merge($request->all(), ['image_name' => $filename]));

                return response()->json([
                    'code' => 201,
                    'status' => 'success',
                    'data' => $user,
                    'message' => 'Mise à jour avec succès'
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
                'error' => $e->getMessage(),
            ]);
        }
    }
}
