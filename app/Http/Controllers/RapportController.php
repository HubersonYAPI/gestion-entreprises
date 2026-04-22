<?php

namespace App\Http\Controllers;

use App\Models\Declaration;
use App\Models\Entreprise;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RapportController extends Controller
{
    public function index(Request $request)
    {
        $query = Declaration::with(['entreprise', 'paiement'])
            ->orderByDesc('created_at');
 
        // ── Filtres ───────────────────────────────────────────────
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('secteur')) {
            $query->where('secteur_activite', $request->secteur);
        }
        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('reference', 'ilike', "%$s%")
                  ->orWhereHas('entreprise', fn($eq) => $eq->where('nom', 'ilike', "%$s%"));
            });
        }
 
        // ── Export CSV ────────────────────────────────────────────
        if ($request->has('export')) {
            return $this->exportCsv($query->get());
        }
 
        $declarations = $query->where('phase', '>', 1)->paginate(20)->withQueryString();
 
        // ── Agrégats pour le résumé ───────────────────────────────
        $totalMontant  = Paiement::whereIn(
            'declaration_id', $query->pluck('id')
        )->sum('montant');
 
        $secteurs = Declaration::select('secteur_activite')
            ->whereNotNull('secteur_activite')
            ->distinct()
            ->orderBy('secteur_activite')
            ->pluck('secteur_activite');
 
        $statuts = Declaration::select('statut')
            ->distinct()
            ->pluck('statut');
 
        return view('agent.rapports', compact(
            'declarations', 'totalMontant', 'secteurs', 'statuts'
        ));
    }
 
    private function exportCsv($declarations)
    {
        $filename = 'rapport_declarations_' . now()->format('Ymd_His') . '.csv';
 
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
 
        $callback = function () use ($declarations) {
            $handle = fopen('php://output', 'w');
            // BOM UTF-8 pour Excel
            fwrite($handle, "\xEF\xBB\xBF");
 
            fputcsv($handle, [
                'Référence', 'Entreprise', 'Secteur', 'Nature d\'activité',
                'Statut', 'Phase', 'Montant payé', 'Date création'
            ], ';');
 
            foreach ($declarations as $d) {
                fputcsv($handle, [
                    $d->reference,
                    $d->entreprise->nom ?? '—',
                    $d->secteur_activite ?? '—',
                    $d->nature_activite  ?? '—',
                    $d->statut,
                    $d->phase            ?? '—',
                    $d->paiement->montant ?? 0,
                    $d->created_at->format('d/m/Y'),
                ], ';');
            }
 
            fclose($handle);
        };
 
        return response()->stream($callback, 200, $headers);
    }
}
