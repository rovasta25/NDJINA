<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.email' => 'L\'adresse e-mail n\'est pas valide.',
            'password.required' => 'Le mot de passe est obligatoire.',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if ($user && $user->isAdmin()) {
            return back()
                ->withErrors(['email' => 'Ces identifiants ne correspondent à aucun compte.'])
                ->withInput($request->only('email'));
        }

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Ces identifiants ne correspondent à aucun compte.'])
                ->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function showAdmin()
    {
        if (Auth::user()?->isAdmin()) {
            return redirect()->route('admin.index');
        }

        return view('auth.login-admin');
    }

    public function storeAdmin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.email' => 'L\'adresse e-mail n\'est pas valide.',
            'password.required' => 'Le mot de passe est obligatoire.',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !$user->isAdmin()) {
            abort(404);
        }

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Ces identifiants ne correspondent à aucun compte.'])
                ->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.index'));
    }
}
