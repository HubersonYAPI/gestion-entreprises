<?php

namespace App\Http\Controllers;

use App\Models\Declaration;
use App\Models\Document;
use App\Models\Entreprise;
use App\Models\Gerant;
use App\Services\HistoriqueService;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentController extends Controller
{
    public function dashboard(Request $request)
    {
        $query = Declaration::with(['entreprise', 'entreprise.gerant'])
            ->where('phase', '>', 1);

        // Détection du filtre via la route
        if ($request->routeIs('agent.declarations.soumis')) {
            $query->where('statut', 'soumis');
        }

        if ($request->routeIs('agent.declarations.approuver')) {
            $query->where('statut', 'approuve');
        }

        if ($request->routeIs('agent.declarations.payer')) {
            $query->where('statut', 'paye');
        }

        if ($request->routeIs('agent.declarations.en-traitement')) {
            $query->where('statut', 'en_traitement');
        }

        if ($request->routeIs('agent.declarations.valider')) {
            $query->where('statut', 'valide');
        }

        if ($request->routeIs('agent.declarations.rejeter')) {
            $query->where('statut', 'rejete');
        }

        $declarations = $query->latest('updated_at')->paginate(10);

        // Statistiques pour le dashboard
        $stats = [
            'total'         => Declaration::where('phase', '>', 1)->count(),
            'soumis'        => Declaration::where('statut', 'soumis')->count(),
            'approuve'      => Declaration::where('statut', 'approuve')->count(),
            'paye'          => Declaration::where('statut', 'paye')->count(),
            'en_traitement' => Declaration::where('statut', 'en_traitement')->count(),
            'valide'        => Declaration::where('statut', 'valide')->count(),
            'rejete'        => Declaration::where('statut', 'rejete')->count(),
        ];

        return view('agent.dashboard', compact('declarations',  'stats'));
    }

    public function show(Declaration $declaration)
    {
        $declaration->load(['documents', 'entreprise.gerant', 'historiques.user']);
 
        return view('agent.show', compact('declaration'));
    }

    /**
     * Historique d'une déclaration
     * Route : GET /agent/declarations/{declaration}/historique
     */
    public function historique(Declaration $declaration)
    {
        $declaration->load(['historiques.user', 'entreprise.gerant']);
 
        return view('agent.historique', compact('declaration'));
    }

    /**
     * Valider document
     */
    public function validerDocument(Document $document)
    {
        $document->update([
            'statut' => 'valide',
        ]);

        // Met à jour updated_at de la déclaration liée
        $document->declaration->touch();

        // Touch ou ce code
        // $document->declaration->update([
        //     'updated_at' => now()
        // ]);

        return back()->with('success', $document->type . ' validé');
    }

    /**
     * Rejeter document
     */
    public function rejeterDocument(Document $document)
    {
        $document->update([
            'statut' => 'rejete',
        ]);

        // Met à jour updated_at de la déclaration liée
        $document->declaration->touch();

        return back()->with('error', $document->type . ' rejeté');
    }

    /**
     * Valider Declaration
     */
    public function valider(Declaration $declaration)
    {
        $documents = $declaration->documents;

        // Vérifier documents non validés
        $invalides = $documents->where('statut', '!=', 'valide');

        if ($invalides->count() > 0) {
            $liste = $invalides->map(function ($document) {
                return $document->type . ' (' . $document->statut . ')';
            })->implode(', ');

            return back()->with('error', 'Impossible de valider. Documents non conformes : ' . $liste);
        }

        $ancienStatut = $declaration->statut;
        
        // Tout est OK
        $dateLimite = Carbon::now()->addHours(72);

        $declaration->update([
            'statut' => 'en_attente_paiement',
            'validated_at' => Carbon::now(),
            'date_limite_paiement' => $dateLimite,
            'phase' => 3,

        ]);

        // Historique + Notification
        HistoriqueService::enregistrer($declaration, 'approuve', request(), 'Tous les documents sont conformes.', $ancienStatut);
        NotificationService::notifier($declaration, 'approuve');

        return redirect()->route('agent.dashboard')->with('success', 'Declaration approuvée avec succès');
    }

    /**
     * Rejeter Declaration
     */
    public function rejeter(Request $request, Declaration $declaration)
    {
        $request->validate([
            'commentaire' => 'required',
        ]);

        $ancienStatut = $declaration->statut;

        $declaration->update([
            'statut' => 'rejete',
            'commentaire' => $request->commentaire,
        ]);

        // Historique + Notification
        HistoriqueService::enregistrer($declaration, 'rejete', $request, $request->commentaire, $ancienStatut);
        NotificationService::notifier($declaration, 'rejete', $request->commentaire);
        
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
        $declaration->load(['documents', 'entreprise']);
        $documents = $declaration->documents;

        return view('agent.documents', compact('declaration', 'documents'));
    }

    /**
     * Listes des entreprises
     */
    public function entreprises(Request $request)
    {
        $query = Entreprise::with('gerant');

        if ($request->search) {
            $query->where('nom', 'like', '%' . $request->search . '%');
        }

        $entreprises = $query->latest('updated_at')->paginate(10);

        return view('agent.entreprises', compact('entreprises'));
    }

    /**
     * Listes des Gerants
     */
    public function gerants(Request $request)
    {
        $query = Gerant::with('entreprises');

        if ($request->search) {
            $search = strtolower(trim($request->search));

            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nom) LIKE ?', ["%$search%"])
                ->orWhereRaw('LOWER(prenoms) LIKE ?', ["%$search%"])
                ->orWhereRaw("LOWER(CONCAT(nom, ' ', prenoms)) LIKE ?", ["%$search%"]);
            });
        }

        $gerants = $query->orderByDesc('id')
                        ->paginate(10)
                        ->withQueryString();

        return view('agent.gerants', compact('gerants'));
    }
}

