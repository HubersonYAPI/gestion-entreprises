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

        // Enregistrement
        Attestation::create([
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

        return back()->with('success', 'Déclaration terminée + attestation générée');
    }
}
