<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EntrepriseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $gerant = Auth::user()->gerant;

        if (!$gerant) {
            return redirect()->route('gerant.edit')->with('error', 'Veillez mettre à jour les informations du gérant');
        }

        $entreprises = $gerant->entreprises;

        return view('entreprises.index', compact('entreprises'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('entreprises.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'rccm' => 'required',
            'adresse' => 'required',
            'type_entreprise' => 'required',
            'secteur_activite' => 'required',
        ]);

        $gerant = Auth::user()->gerant;

        $entreprise = $gerant->entreprises()->create([
            'nom' => $request->nom,
            'rccm' => $request->rccm,
            'adresse' => $request->adresse,
            'type_entreprise' => $request->type_entreprise,
            'secteur_activite' => $request->secteur_activite,
            'gerant_id' => $gerant->id,
        ]);

        // ── Log audit ─────────────────────────────────────────────
        activity('entreprises')
            ->causedBy(Auth::user())
            ->performedOn($entreprise)
            ->withProperties([
                'nom'              => $entreprise->nom,
                'rccm'             => $entreprise->rccm,
                'secteur_activite' => $entreprise->secteur_activite,
                'type_entreprise'  => $entreprise->type_entreprise,
                'gerant'           => $gerant->nom . ' ' . $gerant->prenoms,
            ])
            ->log('entreprise créée');

        return redirect()->route('entreprises.index')->with('success', 'Entreprise créée avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(Entreprise $entreprise)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Entreprise $entreprise)
    {
        $this->authorizeAccess($entreprise);
        
        return view('entreprises.edit', compact('entreprise'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Entreprise $entreprise)
    {
        $this->authorizeAccess($entreprise);

        $request->validate([
            'nom' => 'required',
            'rccm' => 'required',
            'adresse' => 'required',
            'type_entreprise' => 'required',
            'secteur_activite' => 'required',
        ]);

        // Capture avant modification
        $anciennesValeurs = $entreprise->only([
            'nom', 'rccm', 'adresse', 'type_entreprise', 'secteur_activite'
        ]);

        $entreprise->update($request->all());

        // ── Log audit ─────────────────────────────────────────────
        activity('entreprises')
            ->causedBy(Auth::user())
            ->performedOn($entreprise)
            ->withProperties([
                'avant' => $anciennesValeurs,
                'apres' => $entreprise->only([
                    'nom', 'rccm', 'adresse', 'type_entreprise', 'secteur_activite'
                ]),
            ])->log('entreprise modifiée');

        return redirect()->route('entreprises.index')->with('success', 'Entreprise mise à jour');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Entreprise $entreprise)
    {
        $this->authorizeAccess($entreprise);

        // ── Log audit AVANT suppression ───────────────────────────
        activity('entreprises')
            ->causedBy(Auth::user())
            ->performedOn($entreprise)
            ->withProperties([
                'nom'    => $entreprise->nom,
                'rccm'   => $entreprise->rccm,
                'gerant' => Auth::user()->gerant->nom . ' ' . Auth::user()->gerant->prenoms,
            ])->log('entreprise supprimée');

        $entreprise->delete();

        return redirect()->route('entreprises.index')->with('success', 'Entreprise Supprimée');
    }

    /**
     * Vérifie que l'entreprise appartient au gérant connecté.
     * Sinon → erreur 403 (Accès interdit).
     */
    private function authorizeAccess(Entreprise $entreprise): void
    {
        if ($entreprise->gerant_id !== Auth::user()->gerant->id) {
            abort(403, 'Accès non autorisé.');
        }
    }
}
