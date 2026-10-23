<?php

namespace App\Http\Controllers;

use App\Models\Pharmacie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminPharmacieController extends Controller
{
    public function index()
    {
        // Les pharmacies en attente (validee = 0) s'affichent en premier.
        $pharmacies = Pharmacie::with('user')
            ->orderBy('validee')
            ->orderBy('nom')
            ->get();

        return view('admin.pharmacies', compact('pharmacies'));
    }

    public function validation(Request $request, Pharmacie $pharmacie)
    {
        $request->validate(['validee' => ['required', 'boolean']]);

        $validee = $request->boolean('validee');
        $pharmacie->update(['validee' => $validee]);

        // Journal d'audit : qui a fait quoi, et quand.
        Log::info('Validation de pharmacie', [
            'admin_id' => $request->user()->id,
            'pharmacie_id' => $pharmacie->id,
            'validee' => $validee,
        ]);

        return back()->with('succes', $validee
            ? $pharmacie->nom.' est maintenant validée.'
            : $pharmacie->nom.' est suspendue.');
    }
}
