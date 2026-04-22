<x-agent-layout>
<x-slot name="pageTitle">Documents</x-slot>

    <div class="pg">
        
        {{-- Header --}}
        <div class="pg-hd">
            <div>
                <div class="pg-title">Documents — {{ $declaration->reference }}</div>
                <div class="pg-ref">{{ $declaration->entreprise->nom ?? '—' }} · {{ $declaration->documents->count() }} document(s)</div>
            </div>
            <a href="{{ route('agent.dashboard') }}" class="pg-back">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Retour
            </a>
        </div>

        @if(session('success'))
        <div class="a-ok"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>{{ session('success') }}</div>
        @endif

        {{-- Stats mini --}}
        @php
            $total   = $declaration->documents->count();
            $valides = $declaration->documents->where('statut','valide')->count();
            $rejetes = $declaration->documents->where('statut','rejete')->count();
            $attente = $total - $valides - $rejetes;
            $pct     = $total > 0 ? round($valides / $total * 100) : 0;
        @endphp
        <div class="stats-mini">
            <div class="sm">
                <div class="sm-ico" style="background:#eff6ff;color:#2563eb"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/></svg></div>
                <div><div class="sm-val">{{ $total }}</div><div class="sm-lbl">Total docs</div></div>
            </div>
            <div class="sm">
                <div class="sm-ico" style="background:#ecfdf5;color:#059669"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></div>
                <div><div class="sm-val">{{ $valides }}</div><div class="sm-lbl">Validés</div></div>
            </div>
            <div class="sm">
                <div class="sm-ico" style="background:#fef9c3;color:#ca8a04"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/></svg></div>
                <div><div class="sm-val">{{ $attente }}</div><div class="sm-lbl">En attente</div></div>
            </div>
            <div class="sm">
                <div class="sm-ico" style="background:#fef2f2;color:#dc2626"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></div>
                <div><div class="sm-val">{{ $rejetes }}</div><div class="sm-lbl">Rejetés</div></div>
            </div>
            <div class="sm" style="flex-direction:column;align-items:flex-start;min-width:180px">
                <div style="display:flex;justify-content:space-between;width:100%">
                    <span class="sm-lbl">Progression validation</span>
                    <span style="font-size:.72rem;font-weight:700;color:var(--accent)">{{ $pct }}%</span>
                </div>
                <div class="prog-bar" style="width:100%"><div class="prog-fill" style="width:{{ $pct }}%"></div></div>
            </div>
        </div>

        {{-- Info entreprise --}}
        <div class="ic">
            <div class="ic-h">
                <div class="ic-hico" style="background:#eff6ff;color:#2563eb"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg></div>
                <div class="ic-htit">Informations de l'entreprise</div>
            </div>
            <div class="ic-b">
                <div class="igrid">
                    <div class="field"><span class="field-l">Nom</span><span class="field-v">{{ $declaration->entreprise->nom ?? '—' }}</span></div>
                    <div class="field"><span class="field-l">RCCM</span><span class="field-v">{{ $declaration->entreprise->rccm ?? '—' }}</span></div>
                    <div class="field"><span class="field-l">Adresse</span><span class="field-v">{{ $declaration->entreprise->adresse ?? '—' }}</span></div>
                    <div class="field"><span class="field-l">Type</span><span class="field-v">{{ $declaration->entreprise->type_entreprise ?? '—' }}</span></div>
                    <div class="field"><span class="field-l">Secteur</span><span class="field-v">{{ $declaration->entreprise->secteur_activite ?? '—' }}</span></div>
                </div>
            </div>
        </div>

        {{-- Table documents --}}
        <div class="ic">
            <div class="ic-h">
                <div class="ic-hico" style="background:#fef9c3;color:#ca8a04"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8"/></svg></div>
                <div class="ic-htit">Liste des documents</div>
            </div>

            <div class="tw">
                <table>
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Statut</th>
                            <th>Entreprise</th>
                            <th>Nature</th>
                            <th>Secteur</th>
                            <th>Fichier</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($declaration->documents as $doc)
                    <tr>
                        <td class="nm">{{ $doc->type }}</td>
                        <td>
                            @if($doc->statut === 'valide')   <span class="ds-v">Validé</span>
                            @elseif($doc->statut === 'rejete') <span class="ds-r">Rejeté</span>
                            @else <span class="ds-p">En attente</span>
                            @endif
                        </td>
                        <td>{{ $declaration->entreprise->nom ?? '—' }}</td>
                        <td class="mt">{{ $declaration->nature_activite }}</td>
                        <td class="mt">{{ $declaration->secteur_activite }}</td>
                        <td>
                            <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank" class="vl">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                Ouvrir
                            </a>
                        </td>
                        <td x-data="{op:false}">
                            <div class="dpo">
                                <button @click="op=!op" class="dpo-btn" title="Actions">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                                </button>
                                <div class="dpo-menu" x-show="op" x-transition @click.away="op=false">
                                    @if($doc->statut !== 'valide')
                                    <form action="{{ route('agent.documents.valider', $doc) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dpo-item ok">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                            Valider
                                        </button>
                                    </form>
                                    @endif
                                    @if($doc->statut !== 'rejete')
                                    <form action="{{ route('agent.documents.rejeter', $doc) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dpo-item rj">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                            Rejeter
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="abar">
                <a href="{{ route('agent.dashboard') }}" class="btn-back">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Tableau de bord
                </a>
                <a href="{{ route('agent.declarations.show', $declaration) }}" class="btn-decl">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
                    Voir la déclaration
                </a>
            </div>
        </div>

    </div>
</x-agent-layout>