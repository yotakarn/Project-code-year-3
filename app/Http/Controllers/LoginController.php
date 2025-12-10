<?php

namespace App\Http\Controllers;
use Illuminate\View\View;
use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
     function showLoginForm(): View
    {
        return view('logins.form');
    }

    function logout(): RedirectResponse
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login');
    }

    function authenticate(ServerRequestInterface $request): RedirectResponse
    {
        $validator = Validator::make(
            $request->getParsedBody(),
            [
                'email' => 'required',
                'password' => 'required',
            ],
        );

        if (
            $validator->passes() &&
            Auth::attempt(
                $validator->safe()->only(['email', 'password']),
            )
        ) {
            session()->regenerate();

            return redirect()->intended(route('home.view'));
        }

        $validator
            ->errors()
            ->add(
                'credentials',
                'Email or Password is incorrect.',
            );
        return redirect()
            ->back()
            ->withErrors($validator);
        }
}
