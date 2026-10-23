@extends('layouts.app')

@section('contenu')
    <h1>Espace pharmacien</h1>

    @if ($pharmacie)
        <article class="carte">
            <h2>{{ $pharmacie->nom }}</h2>
            <p>{{ $pharmacie->adresse }} ({{ $pharmacie->quartier }})</p>
            <p>
                Statut :
                @if ($pharmacie->de_garde)
                    <span class="badge">De garde</span>
                @else
                    Pas de garde actuellement
                @endif
            </p>
            <p>{{ $nbDisponibles }} médicaments disponibles sur {{ $nbStocks }} suivis.</p>
        </article>
    @else
        <p class="vide">Aucune pharmacie n'est rattachée à votre compte.</p>
    @endif
@endsection
