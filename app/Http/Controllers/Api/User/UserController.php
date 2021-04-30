<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\User\UpdateUser;

class UserController
{
    public function index(Request $request)
    {
        return $request->user();
    }

    public function update(UpdateUser $request)
    {
        /** @var User $user */
        $user = $request->user();

        if ($request->has('password')) {
            $user->password = $request->password;
        }

        $update_user = $user->save();

        if (!$update_user) {
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
