<?php

namespace App\Http\Controllers;

use App\Models\Declaration;
use App\Models\Paiement;
use App\Services\HistoriqueService;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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
                'statut' => 'rejete'
            ]);

            // ── Log expiration ────────────────────────────────────
            activity('paiements')
                ->causedBy(Auth::user())
                ->performedOn($declaration)
                ->withProperties([
                    'reference'            => $declaration->reference,
                    'date_limite_paiement' => $declaration->date_limite_paiement->toDateTimeString(),
                ])->log('paiement expiré — délai dépassé');

            return redirect()->route('declarations.index')->with('error', 'Delai de paiement expiré');
        }

        //Empêcher double paiement
        if ($declaration->paiement) {
            return back()->with('error', 'Paiement déjà effectué');
        }

        //Créer paiement
        $paiement = Paiement::create([
            'declaration_id' => $declaration->id,
            'montant' => 10000,
            'reference' => 'PAY-' . strtoupper(Str::random(8)),
            'statut' => 'paye',
            'date_paiement' => Carbon::now(),
        ]);

        //Actualiser statut déclaration

        $ancienStatut = $declaration->statut;

        $declaration->update([
            'statut' => 'paye',
            'phase' => 4,
            'paid_at' =>Carbon::now(),
        ]);

        HistoriqueService::enregistrer($declaration, 'paye', request(), 'Paiement effectué par le gérant.', $ancienStatut);
        NotificationService::notifier($declaration, 'paye');

        // ── Log audit ─────────────────────────────────────────────
        activity('paiements')
            ->causedBy(Auth::user())
            ->performedOn($declaration)
            ->withProperties([
                'declaration_ref' => $declaration->reference,
                'paiement_ref'    => $paiement->reference,
                'montant'         => $paiement->montant,
                'ancien_statut'   => $ancienStatut,
                'nouveau_statut'  => 'paye',
                'paid_at'         => $declaration->paid_at->toDateTimeString(),
            ])->log('paiement effectué');

        return redirect()->route('declarations.index')->with('success', 'Paiement effectué avec succès');
    }

    /**
     * Sécurité centralisée
     */
    private function authorizeAccess($declaration)
    {
        if ($declaration->entreprise->gerant_id !== Auth::user()->gerant->id) {
            abort(403, 'Accès non autorisé.');
        }
    }
}
