<?php

namespace App\Http\Controllers;

use App\Models\Declaration;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function index(Declaration $declaration)
    {
        $documents = $declaration->documents;

         // Types déjà utilisés (pour ta vue)
        $typesDejaPresents = $documents->pluck('type')->toArray();

        return view('documents.index', compact('declaration', 'documents', 'typesDejaPresents'));
    }

    public function store(Request $request, Declaration $declaration)
    {
        $request->validate([
            'type' => 'required',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:1024',
        ]);

        // Sécurité anti-doublon (IMPORTANT)
        $exists = $declaration->documents()
            ->where('type', $request->type)
            ->exists();

        if ($exists) {
            return back()->with(
                'error',
                'Ce type de document est déjà ajouté.'
            );
        }

        // Ajouté fichier
        $filePath = $request->file('file')->store('documents', 'public');

        // Enregistrement
        Document::create([
            'declaration_id' => $declaration->id,
            'type' => $request->type,
            'file_path' => $filePath,
        ]);

        return back()->with('success', 'Document Ajouté avec succès');
    }

    public function destroy(Document $document)
    {
        $user = Auth::user();

        // Vérification d'accès (IMPORTANT)
        if ($document->declaration->entreprise->gerant_id !== $user->gerant->id) {
            abort(403);
        }

        // Supprimer le fichier du storage
        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return back()->with('success', 'Document Supprimé');
    }
}
