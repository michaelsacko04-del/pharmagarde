@extends('layouts.app')

@section('contenu')
    <h1>Mon stock</h1>

    @if ($pharmacie)
        <p>{{ $pharmacie->nom }} ({{ $pharmacie->quartier }})</p>

        @if (session('succes'))
            <p class="succes">{{ session('succes') }}</p>
        @endif

        @error('quantite')
            <p class="erreur">{{ $message }}</p>
        @enderror

        @foreach ($stocks as $stock)
            <form method="POST" action="{{ route('pharmacien.stocks.update', $stock) }}" class="carte ligne-stock">
                @csrf
                @method('PUT')
                <strong>{{ $stock->medicament->nom }}</strong>

                <label class="case">
                    <input type="hidden" name="disponible" value="0">
                    <input type="checkbox" name="disponible" value="1" @checked($stock->disponible)>
                    Disponible
                </label>

                <label>Quantité
                    <input type="number" name="quantite" min="0" max="100000" value="{{ $stock->quantite }}">
                </label>

                <button type="submit">Enregistrer</button>
            </form>
        @endforeach

        <p><a href="{{ route('pharmacien.tableau') }}">Retour au tableau de bord</a></p>
    @else
        <p class="vide">Aucune pharmacie n'est rattachée à votre compte.</p>
    @endif
@endsection
