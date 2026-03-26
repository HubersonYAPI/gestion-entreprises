<?php

namespace App\Http\Controllers;

use App\Models\Gerant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GerantController extends Controller
{
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
        $user->gerant()->updateOrCreate(
            ['user_id' => $user->id],
            $data,
        );

        return redirect()->route('gerant.edit')->with('success', 'Profil du Gérant mis à jour');
    }
}
