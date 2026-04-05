<?php

namespace App\Http\Controllers;

use App\Models\Declaration;
use App\Models\Document;
use Illuminate\Http\Request;

use function Symfony\Component\Clock\now;

class AgentController extends Controller
{
    public function dashboard()
    {
        $declarations = Declaration::where('phase', '>', 1)
        ->latest()->get();

        return view('agent.dashboard', compact('declarations'));
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
        $declaration->update([
            'statut' => 'validé',
            'validated_at' => now(),
            'date_limite_paiement' => now()->addHours(48),
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
            'statut' => 'incomplet',
            'phase' => 6,
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

