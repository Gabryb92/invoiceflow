<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);
 
        $user = User::where('email', $request->email)->first();
 
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
 
        return $user->createToken($request->device_name)->plainTextToken;
    }

    public function logout(Request $request){
        //Il metodo user di request ci restituisce l'utente autenticato
        $user = $request->user();
        
        //Eliminiamo i token dal db
        $user->tokens()->delete();

        return response()->json(['message' => "Logout correctly succesfull"]);
    }
}
