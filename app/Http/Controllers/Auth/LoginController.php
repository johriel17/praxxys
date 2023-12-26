<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\FortifiesOthersPasswords;

class LoginController extends Controller
{
    use FortifiesOthersPasswords;

    public function authenticate(Request $request)
    {
        $loginField = $request->input('login');  // Use the provided login field

        $credentials = [
            filter_var($loginField, FILTER_VALIDATE_EMAIL) ? 'email' : 'username' => $loginField,
            'password' => $request->input('password'),
        ];

        // Attempt to log in
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));  // Adjust the intended route as needed
        }

        return back()->withErrors([
            'login' => 'The provided credentials do not match our records.',
        ]);
    }
}