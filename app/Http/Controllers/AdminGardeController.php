<?php

namespace App\Http\Controllers;

use App\Models\Garde;
use App\Models\Pharmacie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AdminGardeController extends Controller
{
    public function index()
    {
        $pharmacies = Pharmacie::where('validee', true)->orderBy('nom')->get();

        $gardes = Garde::with('pharmacie')
            ->whereDate('fin', '>=', today()->toDateString())
            ->orderBy('debut')
            ->get();

        return view('admin.gardes', compact('pharmacies', 'gardes'));
    }

    public function store(Request $request)
    {
        $donnees = $request->validate([
            'pharmacie_id' => ['required', 'integer', Rule::exists('pharmacies', 'id')->where('validee', true)],
            'debut' => ['required', 'date'],
            'fin' => ['required', 'date', 'after_or_equal:debut'],
        ]);

        $pharmacie = Pharmacie::findOrFail($donnees['pharmacie_id']);

        $chevauchement = $pharmacie->gardes()
            ->whereDate('debut', '<=', $donnees['fin'])
            ->whereDate('fin', '>=', $donnees['debut'])
            ->exists();

        if ($chevauchement) {
            return back()
                ->withErrors(['debut' => 'Cette pharmacie a déjà une garde sur cette période.'])
                ->withInput();
        }

        $garde = $pharmacie->gardes()->create([
            'debut' => $donnees['debut'],
            'fin' => $donnees['fin'],
        ]);

        Log::info('Garde créée', [
            'admin_id' => $request->user()->id,
            'pharmacie_id' => $pharmacie->id,
            'garde_id' => $garde->id,
            'debut' => $donnees['debut'],
            'fin' => $donnees['fin'],
        ]);

        return back()->with('succes', 'Garde enregistrée pour '.$pharmacie->nom.'.');
    }

    public function destroy(Request $request, Garde $garde)
    {
        $nom = $garde->pharmacie->nom;
        $garde->delete();

        Log::info('Garde supprimée', [
            'admin_id' => $request->user()->id,
            'garde_id' => $garde->id,
        ]);

        return back()->with('succes', 'Garde supprimée pour '.$nom.'.');
    }
}
