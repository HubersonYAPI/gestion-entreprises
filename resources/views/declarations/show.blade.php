<x-app-layout>
@include('components.ui-styles')
<div class="upg upg-wide">

    {{-- Header --}}
    <div class="upg-hd">
        <div>
            <div class="upg-title">Déclaration — {{ $declaration->reference }}</div>
            <div class="upg-sub">Soumise le {{ $declaration->created_at->format('d/m/Y à H:i') }}</div>
        </div>
        <a href="{{ route('declarations.index') }}" class="ubtn ubtn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Retour
        </a>
    </div>

    {{-- Statut banner --}}
    @php
        $sMap = ['brouillon'=>['Brouillon','ub-gray','#f1f5f9','#475569'],'soumis'=>['Soumis','ub-blue','#dbeafe','#1d4ed8'],'en_traitement'=>['En traitement','ub-yellow','#fef9c3','#92400e'],'valide'=>['Validée','ub-green','#d1fae5','#065f46'],'rejete'=>['Rejetée','ub-red','#fee2e2','#991b1b']];
        [$sl,$sc,$sbg,$stxt] = $sMap[$declaration->statut] ?? [ucfirst($declaration->statut),'ub-gray','#f1f5f9','#475569'];
    @endphp
    <div style="background:{{ $sbg }};border:1px solid {{ $sbg }};border-radius:10px;padding:.85rem 1.2rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem">
        <div style="display:flex;align-items:center;gap:.75rem">
            <span class="ubadge {{ $sc }}" style="font-size:.75rem;padding:4px 10px">{{ $sl }}</span>
            <span style="font-size:.8rem;color:{{ $stxt }};font-weight:600">Phase : {{ $declaration->phase_label }}</span>
        </div>
        @if($declaration->statut === 'brouillon')
        <div class="uactions">
            <a href="{{ route('declarations.edit', $declaration) }}" class="ubtn ubtn-warn ubtn-sm">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Modifier
            </a>
            <form action="{{ route('declarations.submit', $declaration->id) }}" method="POST" style="display:inline">
                @csrf
                <button type="submit" class="ubtn ubtn-ok ubtn-sm" onclick="return confirm('Soumettre cette déclaration ?')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Soumettre
                </button>
            </form>
        </div>
        @endif
    </div>

    {{-- Two-col --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">

        {{-- Gérant --}}
        <div class="ucard">
            <div class="ucard-header">
                <div class="ucard-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Gérant
                </div>
            </div>
            <div class="ucard-body">
                <div class="uinfo-grid" style="grid-template-columns:1fr">
                    <div class="uinfo-field"><span class="uinfo-label">Nom</span><span class="uinfo-value">{{ $declaration->entreprise->gerant->nom }}</span></div>
                    <div class="uinfo-field"><span class="uinfo-label">Prénoms</span><span class="uinfo-value">{{ $declaration->entreprise->gerant->prenoms }}</span></div>
                    <div class="uinfo-field"><span class="uinfo-label">Contact</span><span class="uinfo-value">{{ $declaration->entreprise->gerant->contact }}</span></div>
                </div>
            </div>
        </div>

        {{-- Entreprise --}}
        <div class="ucard">
            <div class="ucard-header">
                <div class="ucard-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
                    Entreprise
                </div>
            </div>
            <div class="ucard-body">
                <div class="uinfo-grid" style="grid-template-columns:1fr">
                    <div class="uinfo-field"><span class="uinfo-label">Nom</span><span class="uinfo-value">{{ $declaration->entreprise->nom }}</span></div>
                    <div class="uinfo-field"><span class="uinfo-label">RCCM</span><span class="uinfo-value" style="font-family:monospace">{{ $declaration->entreprise->rccm }}</span></div>
                    <div class="uinfo-field"><span class="uinfo-label">Adresse</span><span class="uinfo-value">{{ $declaration->entreprise->adresse }}</span></div>
                    <div class="uinfo-field"><span class="uinfo-label">Type</span><span class="uinfo-value">{{ $declaration->entreprise->type_entreprise }}</span></div>
                    <div class="uinfo-field"><span class="uinfo-label">Secteur</span><span class="uinfo-value">{{ $declaration->entreprise->secteur_activite }}</span></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Activité --}}
    <div class="ucard">
        <div class="ucard-header">
            <div class="ucard-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                Informations d'activité
            </div>
        </div>
        <div class="ucard-body">
            <div class="uinfo-grid">
                <div class="uinfo-field"><span class="uinfo-label">Nature d'activité</span><span class="uinfo-value">{{ $declaration->nature_activite }}</span></div>
                <div class="uinfo-field"><span class="uinfo-label">Secteur d'activité</span><span class="uinfo-value">{{ $declaration->secteur_activite }}</span></div>
                <div class="uinfo-field"><span class="uinfo-label">Produits / Services</span><span class="uinfo-value">{{ $declaration->produits }}</span></div>
                <div class="uinfo-field"><span class="uinfo-label">Effectif</span><span class="uinfo-value">{{ $declaration->effectifs }}</span></div>
            </div>
        </div>
    </div>

    {{-- Documents --}}
    <div class="ucard">
        <div class="ucard-header">
            <div class="ucard-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
                Documents soumis
            </div>
            <span style="font-size:.72rem;font-weight:700;background:var(--acc-bg);color:var(--acc-txt);padding:2px 8px;border-radius:20px;">{{ $declaration->documents->count() }} doc(s)</span>
        </div>

        @if($declaration->documents->isEmpty())
            <div class="uempty">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/></svg>
                Aucun document ajouté.
                @if($declaration->statut === 'brouillon')
                    <br><a href="{{ route('documents.index', $declaration) }}" class="ubtn ubtn-primary ubtn-sm" style="margin-top:.75rem;display:inline-flex">Ajouter des documents</a>
                @endif
            </div>
        @else
        <div class="utbl-wrap">
            <table class="utbl">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Statut</th>
                        <th>Fichier</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($declaration->documents as $doc)
                    <tr>
                        <td class="utbl-nm">{{ $doc->type }}</td>
                        <td>
                            @php
                                $dm = ['en_attente'=>['En attente','ub-yellow'],'validé'=>['Validé','ub-green'],'rejeté'=>['Rejeté','ub-red']];
                                [$dl,$dc] = $dm[$doc->statut] ?? [ucfirst($doc->statut),'ub-gray'];
                            @endphp
                            <span class="ubadge {{ $dc }}">{{ $dl }}</span>
                        </td>
                        <td>
                            <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank" class="ufile-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                Voir le fichier
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>
</x-app-layout>