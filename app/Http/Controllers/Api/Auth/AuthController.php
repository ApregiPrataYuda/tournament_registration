<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Users;
use App\Http\Requests\RegistrationValidated;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
   public function register(RegistrationValidated $request)
{
    try {
        $user = Users::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'username' => $request->username ?? null, 
            'email_verified_at' => null,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 2, 
            'phone_number' => $request->phone_number ?? null, 
            'remember_token' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Account registration successfull',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ], 201);
    } catch (\Throwable $e) {
        return response()->json([
            'message' => 'Terjadi kesalahan saat registrasi',
            'error' => config('app.debug') ? $e->getMessage() : 'Silakan coba lagi nanti.',
        ], 500);
    }
}

    public function login(LoginRequest $request)
    {
        

        $user = Users::where('username', $request['username'])->first();

        
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Username tidak ditemukan'
                ], 404);
            }

        if (! $user || ! Hash::check($request['password'], $user->password)) {
            return response()->json([
                'message' => 'username atau password salah',
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }



    public function logout(Request $request)
    {
        // Hapus token yang sedang dipakai
    $request->user()->currentAccessToken()->delete();

    return response()->json([
        'success' => true,
        'message' => 'Logout berhasil',
    ]);
    }


    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }

}
