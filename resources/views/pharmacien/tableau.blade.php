@extends('layouts.app')

@section('contenu')
    <h1>Espace pharmacien</h1>

    @if ($pharmacie)
        <article class="carte">
            <h2>{{ $pharmacie->nom }}</h2>
            <p>{{ $pharmacie->adresse }} ({{ $pharmacie->quartier }})</p>

            @unless ($pharmacie->validee)
                <p class="vide">Votre pharmacie est en attente de validation : elle n'apparaît pas encore dans les recherches.</p>
            @endunless

            <p>
                Statut :
                @if ($pharmacie->de_garde)
                    <span class="badge">De garde</span>
                @else
                    Pas de garde actuellement
                @endif
            </p>
            <p>{{ $nbDisponibles }} médicaments disponibles sur {{ $nbStocks }} suivis.</p>
            <p><a class="bouton" href="{{ route('pharmacien.stocks.index') }}">Gérer mon stock</a></p>
        </article>
    @else
        <p class="vide">Aucune pharmacie n'est rattachée à votre compte.</p>
    @endif
@endsection
