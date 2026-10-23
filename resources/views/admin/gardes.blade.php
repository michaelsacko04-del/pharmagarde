@extends('layouts.app')

@section('contenu')
    <h1>Planning des gardes</h1>

    @if (session('succes'))
        <p class="succes">{{ session('succes') }}</p>
    @endif

    <section class="hero">
        <h2>Ajouter une garde</h2>
        <form method="POST" action="{{ route('admin.gardes.store') }}" class="formulaire">
            @csrf

            <label for="pharmacie_id">Pharmacie</label>
            <select id="pharmacie_id" name="pharmacie_id" required>
                <option value="">Choisir une pharmacie</option>
                @foreach ($pharmacies as $pharmacie)
                    <option value="{{ $pharmacie->id }}" @selected((int) old('pharmacie_id') === $pharmacie->id)>
                        {{ $pharmacie->nom }} ({{ $pharmacie->quartier }})
                    </option>
                @endforeach
            </select>

            <label for="debut">Du</label>
            <input id="debut" type="date" name="debut" value="{{ old('debut') }}" required>

            <label for="fin">Au (inclus)</label>
            <input id="fin" type="date" name="fin" value="{{ old('fin') }}" required>

            @foreach ($errors->all() as $erreur)
                <p class="erreur">{{ $erreur }}</p>
            @endforeach

            <button type="submit">Enregistrer la garde</button>
        </form>
    </section>

    <h2>Gardes en cours et à venir</h2>

    @forelse ($gardes as $garde)
        <article class="carte">
            <h2>{{ $garde->pharmacie->nom }}</h2>
            <p>
                Du {{ $garde->debut->format('d/m/Y') }} au {{ $garde->fin->format('d/m/Y') }}
                @if ($garde->debut->lte(today()))
                    <span class="badge">En cours</span>
                @else
                    <span class="badge attente">À venir</span>
                @endif
            </p>
            <form method="POST" action="{{ route('admin.gardes.destroy', $garde) }}">
                @csrf
                @method('DELETE')
                <button type="submit">Supprimer</button>
            </form>
        </article>
    @empty
        <p class="vide">Aucune garde en cours ou à venir.</p>
    @endforelse

    <p><a href="{{ route('admin.tableau') }}">Retour au tableau de bord</a></p>
@endsection
