<x-app-layout>
@include('components.ui-styles')

<div class="upg upg-wide">

    {{-- ── Header ── --}}
    <div class="upg-hd">
        <div>
            <div class="upg-title">Mes Attestations</div>
            <div class="upg-sub">Téléchargez vos attestations validées.</div>
        </div>
    </div>

    {{-- ── Alertes ── --}}
    @if(session('success'))
    <div class="ua-ok">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <polyline points="20 6 9 17 4 12"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="ua-err">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="12"/>
            <line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        {{ session('error') }}
    </div>
    @endif

    {{-- ── Table ── --}}
    <div class="ucard">
        <div class="ucard-header">
            <div class="ucard-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                    <path d="M14 2v6h6"/>
                    <polyline points="9 15 12 18 15 15"/>
                    <line x1="12" y1="18" x2="12" y2="11"/>
                </svg>
                Liste des attestations
            </div>
            <span style="font-size:.72rem;font-weight:700;background:var(--acc-bg);color:var(--acc-txt);padding:2px 8px;border-radius:20px;">
                {{ $attestations->total() }} attestation(s)
            </span>
        </div>

        <div class="utbl-wrap">
            @if($attestations->isEmpty())
                <div style="padding:3rem;text-align:center;color:var(--t3);font-size:.85rem;">
                    Aucune attestation disponible pour le moment.
                </div>
            @else
            <table class="utbl">
                <thead>
                    <tr>
                        <th>Réf. Déclaration</th>
                        <th>Réf. Attestation</th>
                        <th>Entreprise</th>
                        <th>Type</th>
                        <th>Secteur d'activité</th>
                        <th>Gérant</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($attestations as $attestation)
                    @php
                        $declaration = $attestation->declaration;
                        $entreprise  = $declaration->entreprise ?? null;
                    @endphp
                    <tr>
                        <td class="utbl-mono">{{ $declaration->reference ?? '—' }}</td>
                        <td class="utbl-mono">{{ $attestation->reference }}</td>
                        <td class="utbl-nm">{{ $entreprise->nom ?? '—' }}</td>
                        <td>
                            <span style="font-size:.7rem;font-weight:600;padding:2px 7px;border-radius:6px;background:#f1f5f9;color:var(--t2);border:1px solid var(--border);">
                                {{ $entreprise->type ?? '—' }}
                            </span>
                        </td>
                        <td style="font-size:.8rem;color:var(--t2);">
                            {{ $declaration->secteur_activite ?? '—' }}
                        </td>
                        <td style="font-size:.8rem;color:var(--t2);">
                            {{ $gerant->prenom ?? '' }} {{ $gerant->nom ?? '—' }}
                        </td>
                        <td style="font-size:.74rem;color:var(--t3);white-space:nowrap;">
                            {{ $attestation->created_at->format('d/m/Y') }}
                        </td>
                        <td>
                            <a href="{{ asset('storage/' . $attestation->file_path) }}"
                               target="_blank"
                               class="ubtn ubtn-primary"
                               style="font-size:.72rem;padding:5px 12px;display:inline-flex;align-items:center;gap:5px;text-decoration:none;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                     style="width:13px;height:13px;">
                                    <path d="M12 3v12"/>
                                    <polyline points="7 10 12 15 17 10"/>
                                    <path d="M5 21h14"/>
                                </svg>
                                Télécharger
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>

    {{-- ── Pagination ── --}}
    @if($attestations->hasPages())
        <div class="pager">{{ $attestations->links() }}</div>
    @endif

</div>
</x-app-layout>