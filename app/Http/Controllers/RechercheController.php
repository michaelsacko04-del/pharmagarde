<?php

namespace App\Http\Controllers;

use App\Models\Pharmacie;
use Illuminate\Http\Request;

class RechercheController extends Controller
{
    public function index(Request $request)
    {
        $donnees = $request->validate([
            'q' => ['nullable', 'string', 'min:2', 'max:100'],
            'quartier' => ['nullable', 'string', 'max:100'],
        ]);

        $q = trim($donnees['q'] ?? '');
        $quartier = trim($donnees['quartier'] ?? '');
        $recherche = $q !== '';

        $quartiers = Pharmacie::query()
            ->where('validee', true)
            ->orderBy('quartier')
            ->distinct()
            ->pluck('quartier');

        $pharmacies = null;
        $marqueurs = collect();

        if ($recherche) {
            $filtreMedicament = fn ($m) => $m->where('nom', 'like', '%'.$q.'%');

            $pharmacies = Pharmacie::query()
                ->where('validee', true)
                ->deGarde()
                ->when($quartier !== '', fn ($query) => $query->where('quartier', $quartier))
                ->whereHas('stocks', fn ($s) => $s
                    ->where('disponible', true)
                    ->whereHas('medicament', $filtreMedicament))
                ->with(['stocks' => fn ($s) => $s
                    ->where('disponible', true)
                    ->whereHas('medicament', $filtreMedicament)
                    ->with('medicament')])
                ->orderBy('nom')
                ->paginate(10)
                ->withQueryString();

            // Données envoyées à la carte : uniquement les pharmacies localisées.
            $marqueurs = $pharmacies->getCollection()
                ->filter(fn ($p) => $p->latitude !== null && $p->longitude !== null)
                ->map(fn ($p) => [
                    'nom' => $p->nom,
                    'adresse' => $p->adresse,
                    'telephone' => $p->telephone,
                    'lat' => $p->latitude,
                    'lng' => $p->longitude,
                    'medicaments' => $p->stocks
                        ->map(fn ($s) => $s->medicament->nom.' ('.$s->quantite.' en stock)')
                        ->values()
                        ->all(),
                ])
                ->values()
                ->all();
        }

        return view('recherche.index', compact('q', 'quartier', 'quartiers', 'recherche', 'pharmacies', 'marqueurs'));
    }
}
