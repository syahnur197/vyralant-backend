<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request)
    {
        /** @var User $user */
        $user = User::create($request->validated());

        if (!$user) {

            Log::error('Fail to regiser user: ' . json_encode($request->all()));

            $response = [
                'success' => false,
                'message' => 'Unable to register',
                'user' => $user,
            ];

            return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);

        }

        try {

            $response = [
                'success' => true,
                'message' => 'Successfully register an account!',
            ];

            return response()->json($response, Response::HTTP_CREATED);

        } catch (\Exception $e) {
            Log::error('Fail to register user: ' . json_encode($request->all()));
            Log::error($e);

            $response = [
                'success' => false,
                'message' => 'Unable to register'
            ];

            return response()->json($response, 400);
        }
    }
}
