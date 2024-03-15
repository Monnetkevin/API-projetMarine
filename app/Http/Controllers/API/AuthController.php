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
                // 'image_name' => Avatar::create($request->first_name, $request->last_name)->toBase64(),
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
                'code' => 404,
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
}
