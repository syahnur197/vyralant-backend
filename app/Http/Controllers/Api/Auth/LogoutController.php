<?php

namespace App\Http\Controllers\Api\Auth;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogoutController
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        try {
            Auth::guard('web')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return response()->json([
                'success' => true,
                'message' => 'Log out successfully!',
            ], 400);
        } catch (Exception $e) {

            Log::error("LogoutController" . $e);

            return response()->json([
                'success' => false,
                'message' => 'Fail to log out!',
            ], 400);
        }
    }
}
