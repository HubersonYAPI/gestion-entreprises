<?php

namespace App\Http\Controllers;

use App\Models\Declaration;
use App\Models\Entreprise;
use App\Models\Gerant;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatistiqueController extends Controller
{
    public function index()
    {
        // ── KPIs globaux ──────────────────────────────────────────
        $totalDeclarations  = Declaration::count();
        $totalEntreprises   = Entreprise::count();
        $totalGerants       = Gerant::count();
        $totalPaiements     = Paiement::sum('montant') ?? 0;
 
        // ── Répartition par statut ────────────────────────────────
        $parStatut = Declaration::select('statut', DB::raw('count(*) as total'))
            ->groupBy('statut')
            ->pluck('total', 'statut')
            ->toArray();
 
        $statutLabels  = array_keys($parStatut);
        $statutValues  = array_values($parStatut);
 
        // ── Déclarations par mois (12 derniers mois) ──────────────
        $parMois = Declaration::select(
                DB::raw("TO_CHAR(created_at, 'Mon YYYY') as mois"),
                DB::raw("DATE_TRUNC('month', created_at) as mois_date"),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('mois', 'mois_date')
            ->orderBy('mois_date')
            ->get();
 
        $moisLabels = $parMois->pluck('mois')->toArray();
        $moisValues = $parMois->pluck('total')->toArray();
 
        // ── Déclarations par secteur ──────────────────────────────
        $parSecteur = Declaration::select('secteur_activite', DB::raw('count(*) as total'))
            ->whereNotNull('secteur_activite')
            ->groupBy('secteur_activite')
            ->orderByDesc('total')
            ->limit(8)
            ->pluck('total', 'secteur_activite')
            ->toArray();
 
        $secteurLabels = array_keys($parSecteur);
        $secteurValues = array_values($parSecteur);
 
        // ── Paiements par mois ────────────────────────────────────
        $paiementsParMois = Paiement::select(
                DB::raw("TO_CHAR(created_at, 'Mon YYYY') as mois"),
                DB::raw("DATE_TRUNC('month', created_at) as mois_date"),
                DB::raw('SUM(montant) as total')
            )
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('mois', 'mois_date')
            ->orderBy('mois_date')
            ->get();
 
        $paiementMoisLabels = $paiementsParMois->pluck('mois')->toArray();
        $paiementMoisValues = $paiementsParMois->pluck('total')->toArray();
 
        // ── Taux de validation ────────────────────────────────────
        $validees  = $parStatut['Valide']   ?? $parStatut['valide']   ?? 0;
        $rejetees  = $parStatut['Rejete']   ?? $parStatut['rejete']   ?? 0;
        $soumises  = $parStatut['Soumis']   ?? $parStatut['soumis']   ?? 0;
        $tauxValid = $totalDeclarations > 0
            ? round(($validees / $totalDeclarations) * 100, 1)
            : 0;
 
        return view('agent.statistiques', compact(
            'totalDeclarations', 'totalEntreprises', 'totalGerants', 'totalPaiements',
            'statutLabels', 'statutValues',
            'moisLabels', 'moisValues',
            'secteurLabels', 'secteurValues',
            'paiementMoisLabels', 'paiementMoisValues',
            'validees', 'rejetees', 'soumises', 'tauxValid'
        ));
    }
}
