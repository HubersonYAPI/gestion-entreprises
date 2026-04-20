<x-app-layout>
@include('components.ui-styles')
<div class="upg upg-wide">

    {{-- Header --}}
    <div class="upg-hd">
        <div>
            <div class="upg-title">Documents</div>
            <div class="upg-sub">{{ $declaration->reference }} — {{ $declaration->entreprise->nom }}</div>
        </div>
        <a href="{{ route('declarations.index') }}" class="ubtn ubtn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Retour
        </a>
    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div class="ua-ok">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="ua-err">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        {{ session('error') }}
    </div>
    @endif

    {{-- Statut banner --}}
    @php
        $sMap = ['brouillon'=>['Brouillon','ub-gray','#f1f5f9','#475569'],'soumis'=>['Soumis','ub-blue','#dbeafe','#1d4ed8'],'en_traitement'=>['En traitement','ub-yellow','#fef9c3','#92400e'],'validé'=>['Validée','ub-green','#d1fae5','#065f46'],'rejeté'=>['Rejetée','ub-red','#fee2e2','#991b1b']];
        [$sl,$sc,$sbg,$stxt] = $sMap[$declaration->statut] ?? [ucfirst($declaration->statut),'ub-gray','#f1f5f9','#475569'];
    @endphp
    <div style="background:{{ $sbg }};border:1px solid {{ $sbg }};border-radius:10px;padding:.85rem 1.2rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem">
        <div style="display:flex;align-items:center;gap:.75rem">
            <span class="ubadge {{ $sc }}" style="font-size:.75rem;padding:4px 10px"> Statut : {{ $sl }}</span>
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

    {{-- Infos entreprise --}}
    <div class="ucard">
        <div class="ucard-header">
            <div class="ucard-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
                Informations de l'entreprise
            </div>
        </div>
        <div class="ucard-body">
            <div class="uinfo-grid">
                <div class="uinfo-field"><span class="uinfo-label">Nom</span><span class="uinfo-value">{{ $declaration->entreprise->nom }}</span></div>
                <div class="uinfo-field"><span class="uinfo-label">RCCM</span><span class="uinfo-value" style="font-family:monospace">{{ $declaration->entreprise->rccm }}</span></div>
                <div class="uinfo-field"><span class="uinfo-label">Adresse</span><span class="uinfo-value">{{ $declaration->entreprise->adresse }}</span></div>
                <div class="uinfo-field"><span class="uinfo-label">Type</span><span class="uinfo-value">{{ $declaration->entreprise->type_entreprise }}</span></div>
                <div class="uinfo-field"><span class="uinfo-label">Secteur</span><span class="uinfo-value">{{ $declaration->entreprise->secteur_activite }}</span></div>
            </div>
        </div>
    </div>

    {{-- Upload --}}
    <div class="ucard">
        <div class="ucard-header">
            <div class="ucard-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                Ajouter un document
            </div>
        </div>
        <div class="ucard-body">
            <form action="{{ route('documents.store', $declaration) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="ugrid ugrid-2" style="gap:1rem;margin-bottom:1.25rem">
                    <div class="ufield">
                        @php
                            $types = [
                                'RCCM' => 'RCCM',
                                'CC' => 'Compte Contribuable',
                                'produits' => 'Liste des Produits',
                                'appareils' => 'Liste des Appareils',
                                'formulaire' => 'Formulaire Signé'
                            ];
                        @endphp
                        <label>Type de document *</label>
                        <select name="type" required>
                            <option value="" disabled selected>Sélectionner un type</option>

                            @foreach($types as $key => $label)
                                @if(!in_array($key, $typesDejaPresents))
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="ufield">
                        <label>Fichier *</label>
                        <div class="ufile-zone">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:20px;height:20px;flex-shrink:0;opacity:.4"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                            <input type="file" name="file" required>
                        </div>
                        <span class="ufield-hint">PDF, JPG, PNG acceptés.</span>
                    </div>
                </div>
                <div class="uactions">
                    <button type="submit" class="ubtn ubtn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        Ajouter le document
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Liste des documents --}}
    <div class="ucard">
        <div class="ucard-header">
            <div class="ucard-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8"/></svg>
                Documents soumis
            </div>
            <span style="font-size:.72rem;font-weight:700;background:var(--acc-bg);color:var(--acc-txt);padding:2px 8px;border-radius:20px;">{{ $documents->count() }} doc(s)</span>
        </div>
        @if($documents->isEmpty())
            <div class="uempty">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/></svg>
                Aucun document ajouté pour cette déclaration.
            </div>
        @else
        <div class="utbl-wrap">
            <table class="utbl">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Statut</th>
                        <th>Fichier</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($documents as $doc)
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
                                Voir
                            </a>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('documents.destroy', $doc) }}" onsubmit="return confirm('Supprimer ce document ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="uib uib-del" title="Supprimer">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>
                                </button>
                            </form>
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