@extends('layouts.app')

@section('contenu')
    <h1>Espace administrateur</h1>

    <article class="carte">
        <h2>Vue d'ensemble</h2>
        <ul>
            <li>{{ $nbPharmacies }} pharmacies enregistrées ({{ $nbValidees }} validées, {{ $nbEnAttente }} en attente)</li>
            <li>{{ $nbDeGarde }} pharmacies de garde aujourd'hui</li>
            <li>{{ $nbMedicaments }} médicaments référencés</li>
        </ul>
        <p><a class="bouton" href="{{ route('admin.pharmacies.index') }}">Gérer les pharmacies</a></p>
        <p><a class="bouton" href="{{ route('admin.gardes.index') }}">Planning des gardes</a></p>
    </article>
@endsection
