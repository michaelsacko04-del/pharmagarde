<?php

namespace App\Http\Controllers;

use App\Models\Pharmacie;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $pharmacie = Pharmacie::where('user_id', $request->user()->id)->first();

        $stocks = $pharmacie
            ? $pharmacie->stocks()->with('medicament')->get()
                ->sortBy(fn (Stock $s) => $s->medicament->nom)->values()
            : collect();

        return view('pharmacien.stocks', compact('pharmacie', 'stocks'));
    }

    public function update(Request $request, Stock $stock)
    {
        Gate::authorize('update', $stock);

        $donnees = $request->validate([
            'disponible' => ['required', 'boolean'],
            'quantite' => ['nullable', 'integer', 'min:0', 'max:100000'],
        ]);

        $disponible = $request->boolean('disponible');
        $quantite = $disponible ? (int) ($donnees['quantite'] ?? 0) : 0;

        if ($disponible && $quantite < 1) {
            return back()->withErrors([
                'quantite' => 'Indiquez une quantité supérieure à 0 pour un médicament disponible.',
            ]);
        }

        $stock->update(['disponible' => $disponible, 'quantite' => $quantite]);

        return back()->with('succes', 'Stock mis à jour : '.$stock->medicament->nom.'.');
    }
}
