@extends('layouts.app')

@section('contenu')
    <h1>Pharmacies</h1>

    @if (session('succes'))
        <p class="succes">{{ session('succes') }}</p>
    @endif

    @foreach ($pharmacies as $pharmacie)
        <article class="carte">
            <h2>{{ $pharmacie->nom }}</h2>
            <p>{{ $pharmacie->adresse }} ({{ $pharmacie->quartier }})</p>
            <p>Pharmacien : {{ $pharmacie->user?->email ?? 'aucun compte rattaché' }}</p>
            <p>
                Statut :
                @if ($pharmacie->validee)
                    <span class="badge">Validée</span>
                @else
                    <span class="badge attente">En attente</span>
                @endif
            </p>

            <form method="POST" action="{{ route('admin.pharmacies.validation', $pharmacie) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="validee" value="{{ $pharmacie->validee ? 0 : 1 }}">
                <button type="submit">{{ $pharmacie->validee ? 'Suspendre' : 'Valider' }}</button>
            </form>
        </article>
    @endforeach

    <p><a href="{{ route('admin.tableau') }}">Retour au tableau de bord</a></p>
@endsection
