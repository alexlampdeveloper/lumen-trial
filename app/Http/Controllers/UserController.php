<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'phone' => 'required|string',
        ]);

        $user = $this->userService->register($request->only(['first_name', 'last_name', 'email', 'password', 'phone']));

        return response()->json($user, 201);
    }

    public function signIn(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ]);;
    }

    public function recoverPassword(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);

        $this->userService->recoverPassword($request->get('email'));

        return response()->json(['message' => 'Email sent if user with this email exists'], 200);
    }

    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|string',
            'reset_token' => 'required|string',
        ]);

        try {
            $this->userService->updatePassword($request->get('reset_token'), $request->get('password'));

            return response()->json(['message' => 'Password updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid or expired reset token'], 400);
        }
    }
}
