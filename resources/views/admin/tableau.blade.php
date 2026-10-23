@extends('layouts.app')

@section('contenu')
    <h1>Espace administrateur</h1>

    <article class="carte">
        <h2>Vue d'ensemble</h2>
        <ul>
            <li>{{ $nbPharmacies }} pharmacies enregistrées ({{ $nbValidees }} validées)</li>
            <li>{{ $nbDeGarde }} pharmacies de garde actuellement</li>
            <li>{{ $nbMedicaments }} médicaments référencés</li>
        </ul>
    </article>
@endsection
