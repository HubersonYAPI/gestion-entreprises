<x-agent-layout>

<div class="pg">

    {{-- ── Header ── --}}
    <div class="db-hd">
        <div>
            <div class="db-title">Attestations</div>
            <div class="db-sub">Toutes les attestations générées sur la plateforme.</div>
        </div>
        
        {{-- ── Barre de recherche ── --}}
        <form method="GET" action="{{ route('agent.attestations') }}">
            <div style="display:flex;gap:.5rem;max-width:460px;">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Réf., entreprise, gérant…"
                    style="flex:1;padding:7px 12px;border:1px solid var(--border);border-radius:8px;font-size:.82rem;background:var(--white);color:var(--t1);outline:none;">
                <button type="submit"
                        style="font-size:.79rem;font-weight:700;padding:7px 16px;border-radius:8px;border:none;background:var(--accent);color:#fff;cursor:pointer;">
                    Rechercher
                </button>
                @if(request('search'))
                    <a href="{{ route('agent.attestations') }}"
                    style="font-size:.79rem;font-weight:600;padding:7px 12px;border-radius:8px;border:1px solid var(--border);background:#f8f9fd;color:var(--t2);text-decoration:none;display:inline-flex;align-items:center;">
                        ✕ Effacer
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ── Alertes ── --}}
    @if(session('success'))
    <div class="a-ok">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <polyline points="20 6 9 17 4 12"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="a-err">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="12"/>
            <line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        {{ session('error') }}
    </div>
    @endif

    

    {{-- ── Table ── --}}
    <div class="card">
        <div class="ch">
            <div class="ct" style="display:flex;align-items:center;gap:.5rem;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     style="width:15px;height:15px;color:var(--accent)">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                    <path d="M14 2v6h6"/>
                    <polyline points="9 15 12 18 15 15"/>
                    <line x1="12" y1="18" x2="12" y2="11"/>
                </svg>
                Liste des attestations
            </div>
            <span class="cc">{{ $total }} au total — {{ $attestations->count() }} affichée(s)</span>
        </div>

        <div class="tw">
            @if($attestations->isEmpty())
                <div class="empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                        <path d="M14 2v6h6"/>
                    </svg>
                    Aucune attestation trouvée.
                </div>
            @else
            <table>
                <thead>
                    <tr>
                        <th>Réf. Attestation</th>
                        <th>Réf. Déclaration</th>
                        <th>Entreprise</th>
                        <th>Type</th>
                        <th>Secteur d'activité</th>
                        <th>Gérant</th>
                        <th>Générée le</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($attestations as $attestation)
                    @php
                        $declaration = $attestation->declaration;
                        $entreprise  = $declaration->entreprise ?? null;
                        $gerant      = $entreprise->gerant ?? null;
                    @endphp
                    <tr>
                        <td class="ref">{{ $attestation->reference }}</td>
                        <td class="ref">{{ $declaration->reference ?? '—' }}</td>
                        <td class="nm">{{ $entreprise->nom ?? '—' }}</td>
                        <td>
                            <span class="ph">{{ $entreprise->type ?? '—' }}</span>
                        </td>
                        <td class="mt">{{ $declaration->secteur_activite ?? '—' }}</td>
                        <td style="font-size:.8rem;color:var(--t2);">
                            @if($gerant)
                                {{ $gerant->prenom }} {{ $gerant->nom }}
                            @else
                                —
                            @endif
                        </td>
                        <td style="font-size:.74rem;color:var(--t3);white-space:nowrap;">
                            {{ $attestation->created_at->format('d/m/Y') }}
                        </td>
                        <td>
                            <div class="acts">
                                <a href="{{ asset('storage/' . $attestation->file_path) }}"
                                   target="_blank"
                                   class="vl"
                                   title="Télécharger l'attestation">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <path d="M12 3v12"/>
                                        <polyline points="7 10 12 15 17 10"/>
                                        <path d="M5 21h14"/>
                                    </svg>
                                    Télécharger
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @endif
        </div>

        {{-- ── Pagination ── --}}
        @if($attestations->hasPages())
            <div class="pager">{{ $attestations->withQueryString()->links() }}</div>
        @endif
    </div>

</div>
</x-agent-layout>