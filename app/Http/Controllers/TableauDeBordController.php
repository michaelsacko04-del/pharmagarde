<?php

namespace App\Http\Controllers;

use App\Models\Medicament;
use App\Models\Pharmacie;
use Illuminate\Http\Request;

class TableauDeBordController extends Controller
{
    public function pharmacien(Request $request)
    {
        $pharmacie = Pharmacie::where('user_id', $request->user()->id)->first();
        $nbStocks = $pharmacie?->stocks()->count() ?? 0;
        $nbDisponibles = $pharmacie?->stocks()->where('disponible', true)->count() ?? 0;

        return view('pharmacien.tableau', compact('pharmacie', 'nbStocks', 'nbDisponibles'));
    }

    public function admin()
    {
        return view('admin.tableau', [
            'nbPharmacies' => Pharmacie::count(),
            'nbDeGarde' => Pharmacie::where('de_garde', true)->count(),
            'nbValidees' => Pharmacie::where('validee', true)->count(),
            'nbMedicaments' => Medicament::count(),
        ]);
    }
}
