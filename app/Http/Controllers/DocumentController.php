<?php

namespace App\Http\Controllers;

use App\Models\Declaration;
use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index(Declaration $declaration)
    {
        $documents = $declaration->documents;

        return view('documents.index', compact('declaration', 'documents'));
    }

    public function store(Request $request, Declaration $declaration)
    {
        $request->validate([
            'type' => 'required',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:1024',
        ]);

        $filePath = $request->file('file')->store('documents', 'public');

        Document::create([
            'declaration_id' => $declaration->id,
            'type' => $request->type,
            'file_path' => $filePath,
        ]);

        return back()->with('success', 'Document Ajouté');
    }

    public function destroy(Document $document)
    {
        $document->delete();

        return back()->with('success', 'Document Supprimé');
    }
}
