@extends('layouts.app')

@section('contenu')
    <section class="hero">
        <h1>Trouvez votre médicament avant de vous déplacer</h1>
        <p>Voyez quelles pharmacies de garde l'ont en stock.</p>

        <form method="GET" action="{{ route('recherche') }}" class="recherche">
            <input type="search" name="q" value="{{ $q }}" placeholder="Nom du médicament (ex. paracétamol)" required minlength="2" maxlength="100">
            <select name="quartier">
                <option value="">Tous les quartiers</option>
                @foreach ($quartiers as $nom)
                    <option value="{{ $nom }}" @selected($nom === $quartier)>{{ $nom }}</option>
                @endforeach
            </select>
            <button type="submit">Rechercher</button>
        </form>

        @error('q')
            <p class="erreur">{{ $message }}</p>
        @enderror
    </section>

    @if ($recherche)
        <h2>Résultats pour « {{ $q }} »</h2>

        @forelse ($pharmacies as $pharmacie)
            <article class="carte">
                <h2>{{ $pharmacie->nom }}</h2>
                <span class="badge">De garde</span>
                <p>{{ $pharmacie->adresse }} ({{ $pharmacie->quartier }})</p>
                @if ($pharmacie->telephone)
                    <p>Téléphone : {{ $pharmacie->telephone }}</p>
                @endif
                <ul>
                    @foreach ($pharmacie->stocks as $stock)
                        <li>{{ $stock->medicament->nom }} : disponible ({{ $stock->quantite }} en stock)</li>
                    @endforeach
                </ul>
            </article>
        @empty
            <p class="vide">Aucune pharmacie de garde ne possède ce médicament pour le moment.</p>
        @endforelse

        @if ($pharmacies->hasPages())
            <ul class="pagination">
                @if ($pharmacies->onFirstPage())
                    <li class="disabled">Précédent</li>
                @else
                    <li><a href="{{ $pharmacies->previousPageUrl() }}">Précédent</a></li>
                @endif
                <li>Page {{ $pharmacies->currentPage() }} sur {{ $pharmacies->lastPage() }}</li>
                @if ($pharmacies->hasMorePages())
                    <li><a href="{{ $pharmacies->nextPageUrl() }}">Suivant</a></li>
                @else
                    <li class="disabled">Suivant</li>
                @endif
            </ul>
        @endif
    @endif
@endsection
