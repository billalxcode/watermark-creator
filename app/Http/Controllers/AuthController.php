<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(AuthLoginRequest $request) {
        $credentials = $request->validated();

        $token = Auth::guard("api")->attempt($credentials);
        dd($token);
    }
}
