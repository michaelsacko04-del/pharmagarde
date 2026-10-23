@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
@endpush

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

        @if (count($marqueurs) > 0)
            <div id="carte" aria-label="Carte des pharmacies de garde"></div>
        @endif

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

@if (count($marqueurs) > 0)
    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
                integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <script>
            const marqueurs = {{ Illuminate\Support\Js::from($marqueurs) }};
            const carte = L.map('carte');

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; contributeurs <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(carte);

            const points = [];

            marqueurs.forEach(function (m) {
                // textContent : le texte est affiché tel quel, jamais interprété comme du HTML.
                const fenetre = document.createElement('div');
                const titre = document.createElement('strong');
                titre.textContent = m.nom;
                fenetre.appendChild(titre);

                const lignes = [m.adresse];
                if (m.telephone) { lignes.push('Tél. : ' + m.telephone); }
                m.medicaments.forEach(function (med) { lignes.push(med); });

                lignes.forEach(function (texte) {
                    const ligne = document.createElement('div');
                    ligne.textContent = texte;
                    fenetre.appendChild(ligne);
                });

                L.marker([m.lat, m.lng]).addTo(carte).bindPopup(fenetre);
                points.push([m.lat, m.lng]);
            });

            carte.fitBounds(points, { padding: [30, 30], maxZoom: 15 });
        </script>
    @endpush
@endif
