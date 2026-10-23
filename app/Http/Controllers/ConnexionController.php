<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConnexionController extends Controller
{
    public function afficher()
    {
        return view('auth.connexion');
    }

    public function connecter(Request $request)
    {
        $identifiants = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($identifiants)) {
            return back()
                ->withErrors(['email' => 'Identifiants incorrects.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended($this->destination(Auth::user()));
    }

    public function deconnecter(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('recherche');
    }

    private function destination(User $user): string
    {
        return match ($user->role) {
            'admin' => route('admin.tableau'),
            'pharmacien' => route('pharmacien.tableau'),
            default => route('recherche'),
        };
    }
}
