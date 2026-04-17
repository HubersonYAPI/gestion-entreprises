<?php

namespace App\Http\Controllers;

use App\Models\Declaration;
use App\Models\Paiement;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Carbon\Carbon;

class PaiementController extends Controller
{
    /**
     * Page de paiement
     */
    public function show(Declaration $declaration)
    {
        $this->authorizeAccess($declaration);
        
        return view('paiements.show', compact('declaration'));
    }

    /**
     * Effectuer paiement
     */
    public function payer(Declaration $declaration)
    {
        $this->authorizeAccess($declaration);

        //Vérifier expiration
        if ($declaration->date_limite_paiement && Carbon::now()->greaterThan($declaration->date_limite_paiement)) {
            $declaration->update([
                'statut' => 'expiré'
            ]);

            return redirect()->route('declarations.index')->with('error', 'Delai de paiement expiré');
        }

        //Empêcher double paiement
        if ($declaration->paiement) {
            return back()->with('error', 'Paiement déjà effectué');
        }

        //Créer paiement
        Paiement::create([
            'declaration_id' => $declaration->id,
            'montant' => 10000,
            'reference' => 'PAY-' . strtoupper(Str::random(8)),
            'statut' => 'payé',
            'date_paiement' => now(),
        ]);

        //Actualiser statut déclaration
        $declaration->update([
            'statut' => 'validé',
            'phase' => 4,
            'paid_at' =>now(),
        ]);

        return redirect()->route('declarations.index')->with('success', 'Paiement effectué avec succès');
    }

    /**
     * Sécurité centralisée
     */
    private function authorizeAccess($declaration)
    {
        if ($declaration->entreprise->gerant_id !== Auth::user()->gerant->id) {
            abort(403);
        }
    }
}
