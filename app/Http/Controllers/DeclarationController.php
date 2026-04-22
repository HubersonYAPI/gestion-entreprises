<?php

namespace App\Http\Controllers;

use App\Models\Declaration;
use App\Models\Entreprise;
use App\Services\HistoriqueService;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class DeclarationController extends Controller
{
    private function getDeclarations($request)
    {
        $gerant = Auth::user()->gerant;

        if (!$gerant) {
        return redirect()
            ->route('gerant.edit')
            ->with('error', "Aucun Profil gérant trouvé.")
            ->send();
        }

        $query = Declaration::with(['entreprise', 'attestation:id,declaration_id,file_path'])
            ->whereHas('entreprise', function ($q) use ($gerant) {
                $q->where('gerant_id', $gerant->id);
            });

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        return $query->latest('updated_at')->paginate(10);
    }

    private function getCounts($gerant): array
    {
        $base = Declaration::whereHas('entreprise', fn($q) => $q->where('gerant_id', $gerant->id));

        return [
            'total'    => (clone $base)->count(),
            'brou'     => (clone $base)->where('statut', 'brouillon')->count(),
            'soumis'   => (clone $base)->where('statut', 'soumis')->count(),
            'approuve' => (clone $base)->where('statut', 'approuve')->count(),
            'paye'     => (clone $base)->where('statut', 'paye')->count(),
            'enTrait'  => (clone $base)->where('statut', 'en_traitement')->count(),
            'valide'   => (clone $base)->where('statut', 'valide')->count(),
            'rejete'   => (clone $base)->where('statut', 'rejete')->count(),
        ];
    }

    /**
     * Retourne les expressions SQL pour le formatage de date
     * adaptées au driver actif : PostgreSQL en production, SQLite en test.
     *
     *   PostgreSQL : TO_CHAR / DATE_TRUNC
     *   SQLite     : strftime (pas de TO_CHAR natif)
     */
    private function sqlDateFormat(string $column): array
    {
        if (DB::getDriverName() === 'sqlite') {
            return [
                'mois_label' => DB::raw("strftime('%m/%Y', {$column}) as mois"),
                'mois_trunc' => DB::raw("strftime('%Y-%m', {$column}) as mois_date"),
            ];
        }
 
        // PostgreSQL (production / staging)
        return [
            'mois_label' => DB::raw("TO_CHAR({$column}, 'Mon YYYY') as mois"),
            'mois_trunc' => DB::raw("DATE_TRUNC('month', {$column}) as mois_date"),
        ];
    }

    /**
     * Dashboard gérant
     */
    public function dashboard(Request $request)
    {
        $gerant = Auth::user()->gerant;
 
        if (!$gerant) {
            return redirect()->route('gerant.edit')
                ->with('error', 'Aucun profil gérant trouvé.');
        }
 
        $declarations = $this->getDeclarations($request);
        $counts       = $this->getCounts($gerant);
 
        // ── Base scopée au gérant ─────────────────────────────────
        $base = Declaration::whereHas('entreprise',
            fn($q) => $q->where('gerant_id', $gerant->id)
        );
 
        // ── Répartition par statut ────────────────────────────────
        $parStatut = (clone $base)
            ->select('statut', DB::raw('count(*) as total'))
            ->groupBy('statut')
            ->pluck('total', 'statut')
            ->toArray();
 
        $statutLabels = array_keys($parStatut);
        $statutValues = array_values($parStatut);
 
        // ── Déclarations par mois — driver-aware ──────────────────
        ['mois_label' => $moisLabel, 'mois_trunc' => $moisTrunc] = $this->sqlDateFormat('created_at');
 
        $parMois = (clone $base)
            ->select($moisLabel, $moisTrunc, DB::raw('count(*) as total'))
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('mois', 'mois_date')
            ->orderBy('mois_date')
            ->get();
 
        $moisLabels = $parMois->pluck('mois')->toArray();
        $moisValues = $parMois->pluck('total')->toArray();
 
        // ── Top secteurs ──────────────────────────────────────────
        $parSecteur = (clone $base)
            ->select('secteur_activite', DB::raw('count(*) as total'))
            ->whereNotNull('secteur_activite')
            ->groupBy('secteur_activite')
            ->orderByDesc('total')
            ->limit(6)
            ->pluck('total', 'secteur_activite')
            ->toArray();
 
        $secteurLabels = array_keys($parSecteur);
        $secteurValues = array_values($parSecteur);
 
        // ── KPIs ──────────────────────────────────────────────────
        $totalEntreprises = $gerant->entreprises()->count();
        $validees  = $parStatut['valide'] ?? 0;
        $rejetees  = $parStatut['rejete'] ?? 0;
        $soumises  = $parStatut['soumis'] ?? 0;
        $tauxValid = $counts['total'] > 0
            ? round(($validees / $counts['total']) * 100, 1)
            : 0;
 
        return view('dashboard', compact(
            'declarations', 'counts',
            'statutLabels', 'statutValues',
            'moisLabels',   'moisValues',
            'secteurLabels', 'secteurValues',
            'totalEntreprises',
            'validees', 'rejetees', 'soumises', 'tauxValid'
        ));
    }

    public function index(Request $request)
    {
        $declarations = $this->getDeclarations($request);
        $counts       = $this->getCounts(Auth::user()->gerant);
        return view('declarations.index', compact('declarations', 'counts'));
    }

    /**
     * Formulaire de création d'une declaration
     */
    public function create()
    {
        $entreprises = Auth::user()->gerant->entreprises;

        return view('declarations.create', compact('entreprises'));
    }

    /**
     * Générer référence
     */
    private function generateReference()
    {
        $prefix = 'DECL';
        $date = Carbon::now()->format('ym');

        $count = Declaration::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count() + 1;

        $numero = str_pad($count, 4, '0', STR_PAD_LEFT);

        return "$prefix-$date-$numero";
    }

    /**
     * Enregistrement d'une déclaration dans la BD
     */
    public function store(Request $request)
    {
        $request->validate([
            'entreprise_id' => 'required|exists:entreprises,id',
            'nature_activite' => 'required|string|max:255',
            'secteur_activite' => 'required|string|max:255',
            'produits' => 'required|string',
            'effectifs' => 'required|integer|min:1',
        ]);

        $gerant = Auth::user()->gerant;

        //securité
        $entreprise = $gerant->entreprises()->findOrFail($request->entreprise_id);

        $declaration = $entreprise->declarations()->create([
            'reference' => $this->generateReference(),
            'statut' => 'brouillon',
            'phase' => 1,
            'nature_activite' => $request->nature_activite,
            'secteur_activite' => $request->secteur_activite,
            'produits' => $request->produits,
            'effectifs' => $request->effectifs,
        ]);

        // ── Log historique interne ────────────────────────────────

        HistoriqueService::enregistrer($declaration, 'creation', $request,'Déclaration créée par le gérant.', null);

        // ── Log audit spatie ──────────────────────────────────────
        $message = 'Déclaration créée par le gérant.';

        activity('declarations')
            ->causedBy(Auth::user())
            ->performedOn($declaration)
            ->withProperties([
                'reference'        => $declaration->reference,
                'entreprise'       => $entreprise->nom,
                'nature_activite'  => $declaration->nature_activite,
                'secteur_activite' => $declaration->secteur_activite,
                'effectifs'        => $declaration->effectifs,
            ])->log($message);
        
        return redirect()->route('documents.index', $declaration)->with('success', 'Déclaration enregistrée comme brouillon. Ajoutez maintenant les documents requis.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Declaration $declaration)
    {
        $this->authorizeAccess($declaration);

        $entreprises = Auth::user()->gerant->entreprises;
        $declaration->load('documents');

        return view('declarations.show', compact('declaration', 'entreprises'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Declaration $declaration)
    {
        $this->authorizeAccess($declaration);

        $entreprises = Auth::user()->gerant->entreprises;

        return view('declarations.edit', compact('declaration', 'entreprises'));
    }

    /**
     * Mise à jour
     */
    public function update(Request $request, Declaration $declaration)
    {
        $this->authorizeAccess($declaration);

        $request->validate([
            'entreprise_id'    => 'required',
            'nature_activite'  => 'required',
            'secteur_activite' => 'required',
            'produits'         => 'required',
            'effectifs'        => 'required|integer',
        ]);

        $ancienStatut = $declaration->statut;
        
        // Sauvegarder les anciennes valeurs AVANT modification
        $anciennesValeurs = $declaration->only([
            'nature_activite',
            'secteur_activite',
            'produits',
            'effectifs'
        ]);

        $declaration->update([
            'entreprise_id'    => $request->entreprise_id,
            'nature_activite'  => $request->nature_activite,
            'secteur_activite' => $request->secteur_activite,
            'produits'         => $request->produits,
            'effectifs'        => $request->effectifs,
        ]);

        // ── Log historique interne ────────────────────────────────
        HistoriqueService::enregistrer($declaration, 'modification', $request, 'Informations modifiées par le gérant.', $ancienStatut);

        // ── Log audit spatie ──────────────────────────────────────
        activity('declarations')
            ->causedBy(Auth::user())
            ->performedOn($declaration)
            ->withProperties([
                'avant'  => $anciennesValeurs,
                'apres'  => $declaration->only([
                    'nature_activite', 'secteur_activite', 'produits', 'effectifs'
                ]),
            ])->log('declaration modifiée');

        return redirect()->route('declarations.index')->with('success', 'Déclaration mise à jour');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Declaration $declaration)
    {
        $this->authorizeAccess($declaration);

        // ── Log audit spatie AVANT suppression ────────────────────
        activity('declarations')
            ->causedBy(Auth::user())
            ->performedOn($declaration)
            ->withProperties([
                'reference'  => $declaration->reference,
                'statut'     => $declaration->statut,
                'entreprise' => $declaration->entreprise->nom ?? '—',
            ])->log('declaration supprimée');

        $declaration->delete();

        return back()->with('success', 'Déclaration supprimée');
    }

    //Soumission de la Declaration
    public function submit(Declaration $declaration)
    {
        $this->authorizeAccess($declaration);

        // Types obligatoires
        $typesObligatoires = [
            'RCCM',
            'CC',
            'produits',
            'appareils',
            'formulaire'
        ];

        // Types déjà Ajouté
        $typesPresents = $declaration->documents->pluck('type')->toArray();

        // Vérifier les manquants
        $manquants = array_diff($typesObligatoires, $typesPresents);

        if (!empty($manquants)) {
            $liste = collect($manquants)->map(function ($type) {
                return $type . ' (non ajouté)';
            })->implode(', ');

            // ── Log tentative échouée ─────────────────────────────
            activity('declarations')
                ->causedBy(Auth::user())
                ->performedOn($declaration)
                ->withProperties([
                    'reference'          => $declaration->reference,
                    'documents_manquants' => array_values($manquants),
                ])->log('tentative soumission échouée — documents manquants');

            return back()->with('error', 'Impossible de soumettre. Documents manquants : ' . $liste
            );
        }

        // ✅ OK → soumission

        $ancienStatut = $declaration->statut;

        $declaration->update([
            'statut' => 'soumis',
            'submitted_at' => Carbon::now(),
            'phase' => 2,
        ]);

        // ── Log historique interne ────────────────────────────────
        HistoriqueService::enregistrer($declaration, 'soumis', request(), 'Déclaration soumise par le gérant.', $ancienStatut);
        NotificationService::notifier($declaration, 'soumis');

        // ── Log audit spatie ──────────────────────────────────────
        activity('declarations')
            ->causedBy(Auth::user())
            ->performedOn($declaration)
            ->withProperties([
                'reference'     => $declaration->reference,
                'ancien_statut' => $ancienStatut,
                'nouveau_statut' => 'soumis',
                'submitted_at'  => $declaration->submitted_at,
            ])->log('declaration soumise');

        return back()->with('success', 'Declaration Soumise avec succès.');
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
