<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;

class UserController
{
    public function index(Request $request)
    {
        return $request->user();
    }
}
