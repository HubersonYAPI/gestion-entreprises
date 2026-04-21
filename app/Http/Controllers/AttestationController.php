<?php

namespace App\Http\Controllers;

use App\Models\Attestation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttestationController extends Controller
{
    public function index()
    {
        $gerant = Auth::user()->gerant;

        //abort_if(!$gerant, 403);  ou redirect selon ton besoin

        if (!$gerant) {
            return redirect()->route('gerant.edit')
                ->with('error', 'Aucun profil gérant trouvé.');
        }

        $attestations = Attestation::with(['declaration.entreprise'])
            ->whereHas('declaration.entreprise', function ($q) use ($gerant) {
                $q->where('gerant_id', $gerant->id);
            })
            ->latest()
            ->paginate(10);

        return view('attestations.index', compact('attestations', 'gerant'));
    }

    /**
     * Vue admin — toutes les attestations (tous gérants)
     */
    public function adminIndex(Request $request)
    {
        $query = Attestation::with(['declaration.entreprise.gerant'])
            ->latest();

        // Filtre par recherche (référence déclaration, nom entreprise, nom gérant)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%$search%")
                  ->orWhereHas('declaration', fn($q) => $q->where('reference', 'like', "%$search%"))
                  ->orWhereHas('declaration.entreprise', fn($q) => $q->where('nom', 'like', "%$search%"))
                  ->orWhereHas('declaration.entreprise.gerant', function ($q) use ($search) {
                      $q->where('nom', 'like', "%$search%")
                        ->orWhere('prenoms', 'like', "%$search%");
                  });
            });
        }

        $attestations = $query->paginate(15)->withQueryString();
        $total        = Attestation::count();

        return view('agent.attestations', compact('attestations', 'total'));
    }
}
