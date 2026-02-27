<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Check hardcoded credentials as per spec
        if ($credentials['username'] === 'aldmic' && $credentials['password'] === '123abc123') {
            // Find or create the user in DB
            $user = User::firstOrCreate(
                ['username' => 'aldmic'],
                [
                    'name' => 'Aldmic User',
                    'email' => 'aldmic@bububmovie.com',
                    'password' => Hash::make('123abc123'),
                ]
            );

            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->intended(route('movies.index'));
        }

        return back()->withErrors([
            'username' => __('auth.failed'),
        ])->withInput($request->except('password'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
