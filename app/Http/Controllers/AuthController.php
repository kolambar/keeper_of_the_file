<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Login process for redirect result object
     */
    public static function login(object $kc_user)
    {
        $user = User::updateOrCreate([
            'kc_id' => $kc_user->id
        ], [
            'name' => $kc_user->user['uid'],
            'email' => $kc_user->email,
            'kc_token' => $kc_user->token,
            'kc_refresh_token' => $kc_user->refreshToken,
        ]);
        
        Auth::login($user);
    }

    public static function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
    }
}
