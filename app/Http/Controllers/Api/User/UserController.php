<?php

namespace App\Http\Controllers\Api\User;

use Exception;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\User\UpdateUser;
use App\Http\Resources\Users\UserResource;

class UserController
{
    public function index(Request $request)
    {
        return new UserResource($request->user());
    }

    public function update(UpdateUser $request)
    {
        /** @var User $user */
        $user = $request->user();

        if ($request->has('password')) {
            $user->password = $request->password;
        }

        try {
            if ($request->has('profile_picture')) {
                $file_name = Str::random(60);
                $user->addMediaFromRequest('profile_picture')
                    ->usingName($file_name)
                    ->usingFileName($file_name)
                    ->toMediaCollection('profile_picture');
            }

            $user->save();
        } catch (Exception $error) {
            Log::error($error);
            return response()->json([
                'success' => false,
                'message' => 'Fail to update your profile!',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Successfully update your profile!',
        ]);
    }
}
