<?php

namespace App\Http\Controllers;

use App\Models\Declaration;
use App\Models\Attestation;
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
        $declaration->update([
            'statut' => 'en_traitement',
            'phase' => 5,
            'processed_at' => Carbon::now()
        ]);

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

        // MAJ déclaration
        $declaration->update([
            'statut' => 'terminé',
            'phase' => 6,
            'completed_at' => now(),
        ]);

        return back()->with('success', 'Déclaration terminée + attestation générée');
    }
}
