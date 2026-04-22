<?php

namespace App\Http\Controllers;

use App\Models\Declaration;
use App\Models\Attestation;
use App\Services\HistoriqueService;
use App\Services\NotificationService;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class TraitementController extends Controller
{
    /**
     * Passer en traitement
     */
    public function traiter(Declaration $declaration)
    {
        $ancienStatut = $declaration->statut;

        $declaration->update([
            'statut' => 'en_traitement',
            'processed_at' => Carbon::now()
        ]);

        HistoriqueService::enregistrer($declaration, 'en_traitement', request(), 'Dossier pris en charge par l\'équipe.', $ancienStatut);
        NotificationService::notifier($declaration, 'en_traitement');

        // ── Log audit ─────────────────────────────────────────────
        activity('declarations')
            ->causedBy(Auth::user())
            ->performedOn($declaration)
            ->withProperties([
                'reference'      => $declaration->reference,
                'ancien_statut'  => $ancienStatut,
                'nouveau_statut' => 'en_traitement',
                'processed_at'   => $declaration->processed_at->toDateTimeString(),
                'agent'          => Auth::user()->name,
            ])->log('declaration mise en traitement');

        return back()->with('success', 'Déclaration mise en traitement');
    }

    /**
     * Terminer + générer attestation PDF
     */
    public function terminer(Declaration $declaration)
    {
        // éviter doublon
        if ($declaration->attestation) {
            return back()->with('error', 'Attestation déjà générée');
        }

        // Génération PDF
        $pdf = Pdf::loadView('pdf.attestation', compact('declaration'));

        $fileName = 'attestation_' . $declaration->id . '.pdf';
        $filePath = 'attestations/' . $fileName;

        Storage::disk('public')->put($filePath, $pdf->output());

        // Création attestation
        $attestation = Attestation::create([
            'declaration_id' => $declaration->id,
            'file_path' => $filePath,
            'reference' => 'ATT-' .strtoupper(Str::random(8)),
        ]);

        $ancienStatut = $declaration->statut;

        // MAJ déclaration
        $declaration->update([
            'statut' => 'valide',
            'phase' => 5,
            'completed_at' => now(),
        ]);

        HistoriqueService::enregistrer($declaration, 'valide', request(), null, $ancienStatut);
        NotificationService::notifier($declaration, 'valide');

        // ── Log audit ─────────────────────────────────────────────
        activity('declarations')
            ->causedBy(Auth::user())
            ->performedOn($declaration)
            ->withProperties([
                'reference'         => $declaration->reference,
                'ancien_statut'     => $ancienStatut,
                'nouveau_statut'    => 'valide',
                'attestation_ref'   => $attestation->reference,
                'attestation_path'  => $filePath,
                'completed_at'      => $declaration->completed_at->toDateTimeString(),
                'agent'             => Auth::user()->name,
            ])
            ->log('declaration finalisée — attestation générée');

        return back()->with('success', 'Déclaration terminée + attestation générée');
    }
}
