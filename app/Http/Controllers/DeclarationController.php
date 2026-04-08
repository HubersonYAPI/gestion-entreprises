<?php

namespace App\Http\Controllers;

use App\Models\Declaration;
use App\Models\Entreprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DeclarationController extends Controller
{
    private function getDeclarations($request)
    {
        $gerant = Auth::user()->gerant;

        $query = Declaration::with('entreprise')
            ->whereHas('entreprise', function ($q) use ($gerant) {
                $q->where('gerant_id', $gerant->id);
            });

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        return $query->latest('updated_at')->paginate(10);
    }
    /**
     * Dashboard + liste filtrée des déclarations du gérant
     * Supporte le paramètre ?statut=xxx pour filtrer (identique à AgentController)
     */
    public function dashboard(Request $request)
    {
        $declarations = $this->getDeclarations($request);
        return view('dashboard', compact('declarations'));
    }

    public function index(Request $request)
    {
        $declarations = $this->getDeclarations($request);
        return view('declarations.index', compact('declarations'));
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
        $date = now()->format('ym');

        $count = Declaration::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
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

        $entreprise->declarations()->create([
            'reference' => $this->generateReference(),
            'statut' => 'brouillon',
            'phase' => 1,
            'nature_activite' => $request->nature_activite,
            'secteur_activite' => $request->secteur_activite,
            'produits' => $request->produits,
            'effectifs' => $request->effectifs,
        ]);

        return redirect()->route('declarations.index')->with('success', 'Déclaration créée');
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
            'entreprise_id' => 'required',
            'nature_activite' => 'required',
            'secteur_activite' => 'required',
            'produits' => 'required',
            'effectifs' => 'required|integer',
        ]);

        $declaration->update([
            'entreprise_id' => $request->entreprise_id,
            'nature_activite' => $request->nature_activite,
            'secteur_activite' => $request->secteur_activite,
            'produits' => $request->produits,
            'effectifs' => $request->effectifs,
        ]);

        return redirect()->route('declarations.index')->with('success', 'Déclaration mise à jour');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Declaration $declaration)
    {
        $this->authorizeAccess($declaration);

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

            return back()->with(
                'error',
                'Impossible de soumettre. Documents manquants : ' . $liste
            );
        }

        // ✅ OK → soumission

        $declaration->update([
            'statut' => 'soumis',
            'submitted_at' => now(),
            'phase' => 2,
        ]);

        return back()->with('success', 'Declaration Soumise');
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
