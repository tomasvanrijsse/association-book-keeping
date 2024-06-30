<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request): \Illuminate\Http\RedirectResponse
    {
        $user = User::query()
            ->where('email', $request->input('email'))
            ->get()
            ->first();

        if($user && Hash::check($request->input('password'), $user->password)) {
            Auth::login($user);
            return redirect()->route('home');
        }

        return redirect()->route('login')->withErrors([
            'unknown' => 'Invalid email or password',
        ]);
    }
}
