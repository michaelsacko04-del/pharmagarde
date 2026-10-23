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

        if ($recherche) {
            $filtreMedicament = fn ($m) => $m->where('nom', 'like', '%'.$q.'%');

            $pharmacies = Pharmacie::query()
                ->where('validee', true)
                ->where('de_garde', true)
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
        }

        return view('recherche.index', compact('q', 'quartier', 'quartiers', 'recherche', 'pharmacies'));
    }
}
