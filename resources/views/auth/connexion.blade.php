@extends('layouts.app')

@section('contenu')
    <section class="hero">
        <h1>Connexion</h1>
        <p>Espace réservé aux pharmaciens et à l'administrateur.</p>

        <form method="POST" action="{{ route('connexion.envoyer') }}" class="formulaire">
            @csrf
            <label for="email">Adresse e-mail</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">

            <label for="password">Mot de passe</label>
            <input id="password" type="password" name="password" required autocomplete="current-password">

            @error('email')
                <p class="erreur">{{ $message }}</p>
            @enderror

            <button type="submit">Se connecter</button>
        </form>
    </section>
@endsection
