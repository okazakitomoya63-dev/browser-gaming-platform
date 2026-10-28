<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Administrator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,username|unique:administrators,username|min:4|max:60',
            'password' => 'required|min:5|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'invalid',
                'message' => 'Request body is not valid.',
                'violations' => $validator->errors()
            ], 400);
        }

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'token' => $token
        ], 201);
    }

    public function signin(Request $request)
    {
        $admin = Administrator::where('username', $request->username)->first();
        if ($admin && Hash::check($request->password, $admin->password)) {
            // Pastikan kolom last_login_at ada di migration administrators jika ingin dipakai
            // $admin->update(['last_login_at' => now()]);
            $token = $admin->createToken('admin-token', ['admin'])->plainTextToken;
            return response()->json(['status' => 'success', 'token' => $token], 200);
        }

        $user = User::where('username', $request->username)->first();
        if ($user) {
            // Hapus blok 'trashed()' di bawah jika model User belum menggunakan SoftDeletes
            if (method_exists($user, 'trashed') && $user->trashed()) {
                return response()->json([
                    'status' => 'blocked',
                    'message' => 'User blocked',
                    'reason' => $user->delete_reason ?? 'You have been blocked by an administrator'
                ], 403);
            }

            if (Hash::check($request->password, $user->password)) {
                // $user->update(['last_login_at' => now()]);
                $token = $user->createToken('user-token', ['user'])->plainTextToken;
                return response()->json(['status' => 'success', 'token' => $token], 200);
            }
        }

        return response()->json([
            'status' => 'invalid',
            'message' => 'Wrong username or password'
        ], 401);
    }

    public function signout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['status' => 'success'], 200);
    }
}