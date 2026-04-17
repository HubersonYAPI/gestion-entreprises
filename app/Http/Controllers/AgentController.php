<?php

namespace App\Http\Controllers;

use App\Models\Declaration;
use App\Models\Document;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AgentController extends Controller
{
    public function dashboard(Request $request)
    {
        $query = Declaration::where('phase', '>', 1);

        // Détection du filtre via la route
        if ($request->routeIs('agent.declarations.soumis')) {
            $query->where('statut', 'soumis');
        }

        if ($request->routeIs('agent.declarations.non-paye')) {
            $query->where('statut', 'non_paye');
        }

        if ($request->routeIs('agent.declarations.en-traitement')) {
            $query->where('statut', 'en_traitement');
        }

        if ($request->routeIs('agent.declarations.valider')) {
            $query->where('statut', 'validé');
        }

        if ($request->routeIs('agent.declarations.rejeter')) {
            $query->where('statut', 'rejeté');
        }

        $declarations = $query->latest('updated_at')->paginate(10);

        // Statistiques pour le dashboard
        $stats = [
            'total' => Declaration::where('phase', '>', 1)->count(),
            'soumis' => Declaration::where('statut', 'soumis')->count(),
            'non_paye' => Declaration::where('statut', 'non_paye')->count(),
            'en_traitement' => Declaration::where('statut', 'en_traitement')->count(),
            'validé' => Declaration::where('statut', 'validé')->count(),
            'rejeté' => Declaration::where('statut', 'rejeté')->count(),
        ];

        return view('agent.dashboard', compact('declarations',  'stats'));
    }

    public function show(Declaration $declaration)
    {
        $documents = $declaration->documents;

        return view('agent.show', compact('declaration', 'documents'));
    }

    /**
     * Valider document
     */
    public function validerDocument(Document $document)
    {
        $document->update([
            'statut' => 'validé',
        ]);
        return back()->with('success', $document->type . ' validé');
    }

    /**
     * Rejeter document
     */
    public function rejeterDocument(Document $document)
    {
        $document->update([
            'statut' => 'rejeté',
        ]);

        return back()->with('error', $document->type . ' rejeté');
    }

    /**
     * Valider Declaration
     */
    public function valider(Declaration $declaration)
    {
        $documents = $declaration->documents;

        // Vérifier documents non validés
        $invalides = $documents->where('statut', '!=', 'validé');

        if ($invalides->count() > 0) {
            $liste = $invalides->map(function ($document) {
                return $document->type . ' (' . $document->statut . ')';
            })->implode(', ');

            return back()->with('error', 'Impossible de valider. Documents non conformes : ' . $liste);
        }
        
        // Tout est OK
        $dateLimite = Carbon::now()->addHours(72);

        $declaration->update([
            'statut' => 'en_attente_paiement',
            'validated_at' => Carbon::now(),
            'date_limite_paiement' => $dateLimite,
            'phase' => 3,

        ]);

        return redirect()->route('agent.dashboard')->with('success', 'Declaration validée avec succès');
    }

    /**
     * Rejeter Declaration
     */
    public function rejeter(Request $request, Declaration $declaration)
    {
        $request->validate([
            'commentaire' => 'required',
        ]);

        
        $declaration->update([
            'statut' => 'rejeté',
            'phase' => 5,
            //Ajouter un champ commentaire dans la table Declaration
            //'commentaire' => $request->commentaire,
        ]);
        
        /**
         * Mise à jour des commentaire de chaque document
         */
        // foreach ($declaration->documents as $document) 
        // { 
            // $document->update([ 
            // 'statut' => 'Rejeté', 
            // 'commentaire' => $request->commentaire, 
            // ]); 
        // }

        return redirect()->route('agent.dashboard')->with('error', 'Declaration rejetée');
    }

    public function documents(Declaration $declaration)
    {
        $documents = $declaration->documents;

        return view('agent.documents', compact('declaration', 'documents'));
    }
}

