<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        $credential = ['email' => $request->email, 'password' => $request->password];

        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            $credential = ['username' => $request->email, 'password' => $request->password];
        }

        $attempt = Auth::attempt($credential);

        if (!$attempt) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $request->session()->regenerate();

        /** @var User $user */
        $user = $request->user();

        $response = [
            'success' => true,
            'message' => 'Log in successfully!',
        ];

        return response()->json($response, Response::HTTP_OK);
    }
}
