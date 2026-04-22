<?php

namespace App\Http\Controllers;

use App\Models\Gerant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GerantController extends Controller
{
    public function show()
    {
        $gerant = Auth::user()->gerant;

        if (!$gerant) {
            return redirect()->route('gerant.edit')->with('error', "Aucun Profil gérant trouvé.");
        }

        return view('gerants.show', compact('gerant'));
    }

    public function edit()
    {
        $gerant = Auth::user()->gerant;

        return view('gerants.edit', compact('gerant'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'prenoms' => 'required',
            'contact' => 'required',
            'piece_identite' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:1024',
        ]);

        $user = Auth::user();
        $estNouveau = !$user->gerant; // true si création, false si mise à jour

        $data = $request->only([
            'nom',
            'prenoms',
            'contact',
        ]);

        //upload fichier
        if ($request->hasFile('piece_identite')) {

            // 🔴 supprimer ancien fichier
            if ($user->gerant && $user->gerant->piece_identite) {
                Storage::disk('public')->delete($user->gerant->piece_identite);
            }

            $file = $request->file('piece_identite')->store('pieces', 'public');
            $data['piece_identite'] = $file;
        }

        //create or update
        $gerant = $user->gerant()->updateOrCreate(
            ['user_id' => $user->id],
            $data,
        );

        // ── Log audit ─────────────────────────────────────────────
        activity('gerants')
            ->causedBy($user)
            ->performedOn($gerant)
            ->withProperties([
                'nom'                 => $gerant->nom,
                'prenoms'             => $gerant->prenoms,
                'contact'             => $gerant->contact,
                'piece_mise_a_jour'   => $request->hasFile('piece_identite'),
            ])->log($estNouveau ? 'profil gérant créé' : 'profil gérant mis à jour');
 

        return redirect()->route('gerant.edit')->with('success', 'Profil du Gérant mis à jour');
    }
}
