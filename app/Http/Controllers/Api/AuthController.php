<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Auth\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Filter the request
        $data = $request->validate(
            [
                'name' => 'nullable|string|min:3|max:70',
                'email' => 'required|email|unique:users|max:100',
                'password' => 'required|confirmed|min:6|max:20',
                'device_name' => 'required',
            ],
            [
                'email.required' => "L':attribute est requise",
                'password.required' => "Le :attribute est requis",
                'device_name.required' => "Le :attribute est introuvable mais est requis",

                'email.email' => "L':attribute saisie est incorrecte",
                'email.unique' => "L':attribute saisie (:input) est déjà utilisée par un autre utilisateur",

                'email.max' => "L':attribute doit comporter au plus 100 caractères",
                'name.min' => "Le :attribute doit comporter au moins :value caractères",
                'name.max' => "Le :attribute doit comporter au plus :value caractères",
                'password.min' => "Le :attribute doit comporter au moins :value caractères",
                'password.max' => "Le :attribute doit comporter au plus :value caractères",

                'password.confirmed' => "Le :attribute de confirmation est incorrecte",
            ],
            [
                'name' => 'nom',
                'email' => 'adresse mail',
                'password' => 'mot de passe',
                'device_name' => 'nom de l\'appareil',
            ]
        );

        // Hash Password
        $data['password'] = Hash::make($data['password']);

        // Create user
        $user = User::create($data);

        // Create resource
        $user = new UserResource($user);
        $result = [
            'message' => 'Enregistrement réussi',
            'user' => $user,
        ];

        // Reurn result
        return response()->json($result, Response::HTTP_CREATED);
    }


    public function login(Request $request)
    {
        // Filter the request
        $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required',
                'device_name' => 'required',
            ],
            [
                'email.required' => "L':attribute est requise",
                'password.required' => "Le :attribute est requis",
                'device_name.required' => "Le :attribute est introuvable mais est requis",

                'email.email' => "L':attribute saisie est incorrecte",
            ],
            [
                'email' => 'adresse mail',
                'password' => 'mot de passe',
                'device_name' => 'nom de l\'appareil',
            ]
        );

        // Find User
        $user = User::where('email', $request->email)->first();

        // Check Information providing by user
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Create Access token with Laravel Sanctum
        $token = $user->createToken($request->device_name)->plainTextToken;

        // Reurn result
        return response()->json(['access_token' => $token], Response::HTTP_OK);
    }


    public function logout(Request $request)
    {
        // User authenticate
        $user = auth()->user();

        // Find user
        $user = User::find($user->id);
        if ($user)
            $user->tokens()->delete();

        // Reurn result
        return response()->noContent();
    }
}
