<?php

namespace App\Http\Controllers\Auth;

use \App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        return response()->json($user);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if (!Auth::attempt($request->all())) {
            return response()->json(['error' => 'The provided credentials are incorrect.'], 401);
        }

        return response()->json('OK');
    }

    public function logout()
    {
        Auth::logout();
    }
}
