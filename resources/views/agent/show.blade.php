<x-agent-layout>
<x-slot name="pageTitle">Détail déclaration</x-slot>

{{-- MODAL REJET --}}
<div class="mo" id="moRej">
    <div class="modal">
        <div class="mh">
            <div class="mhl">
                <div class="m-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></div>
                <div class="m-tit">Rejeter la déclaration</div>
            </div>
            <button class="m-cls" type="button" onclick="closeRej()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg></button>
        </div>
        <form id="rejForm" method="POST" action="{{ route('agent.rejeter', $declaration) }}">
            @csrf
            <div class="mbody">
                <div>
                    <label class="m-lbl">Motif du rejet <span style="color:#dc2626">*</span></label>
                    <textarea class="m-ta" name="commentaire" placeholder="Décrivez la raison du rejet…" required></textarea>
                    <div class="m-hint">Ce motif sera transmis au demandeur.</div>
                </div>
            </div>
            <div class="mfot">
                <button type="button" class="btn-cn" onclick="closeRej()">Annuler</button>
                <button type="submit" class="btn-rjc">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    Confirmer
                </button>
            </div>
        </form>
    </div>
</div>

<div class="pg">

    {{-- En-tête --}}
    <div class="pg-hd">
        <div>
            <div class="pg-title">Déclaration — {{ $declaration->reference }}</div>
            <div class="pg-ref">Soumise le {{ $declaration->updated_at->format('d/m/Y') }}</div>
        </div>
        <a href="{{ route('agent.dashboard') }}" class="pg-back">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Retour
        </a>
    </div>

    {{-- Alertes --}}
    @if(session('success'))
    <div class="a-ok"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="a-err"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ session('error') }}</div>
    @endif

    {{-- Statut & Phase --}}
    <div class="ic">
        <div class="ic-h">
            <div class="ic-hico" style="background:#eff2ff;color:#2f54eb"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
            <div class="ic-htit">Statut de la déclaration</div>
        </div>
        <div class="ic-b" style="grid-template-columns:repeat(3,1fr)">
            <div class="field">
                <span class="field-l">Référence</span>
                <span class="field-v" style="font-family:monospace">{{ $declaration->reference }}</span>
            </div>
            <div class="field">
                <span class="field-l">Statut</span>
                @php
                    $sm=['soumis'=>['Soumis','b-soumis'],'non_paye'=>['Non payé','b-np'],'en_traitement'=>['En traitement','b-trait'],'validé'=>['Validée','b-valid'],'rejeté'=>['Rejetée','b-rej']];
                    [$sl,$sc]=$sm[$declaration->statut]??[ucfirst($declaration->statut),'b-def'];
                @endphp
                <span class="field-v"><span class="bx {{ $sc }}">{{ $sl }}</span></span>
            </div>
            <div class="field">
                <span class="field-l">Phase</span>
                <span class="field-v"><span class="ph">{{ $declaration->phase_label }}</span></span>
            </div>
        </div>
    </div>

    {{-- Gérant + Entreprise --}}
    <div class="two">
        {{-- Gérant --}}
        <div class="ic">
            <div class="ic-h">
                <div class="ic-hico" style="background:#f5f3ff;color:#7c3aed"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
                <div class="ic-htit">Gérant</div>
            </div>
            <div class="ic-b">
                <div class="field"><span class="field-l">Nom</span><span class="field-v">{{ $declaration->entreprise->gerant->nom ?? '—' }}</span></div>
                <div class="field"><span class="field-l">Prénoms</span><span class="field-v">{{ $declaration->entreprise->gerant->prenoms ?? '—' }}</span></div>
                <div class="field"><span class="field-l">Contact</span><span class="field-v">{{ $declaration->entreprise->gerant->contact ?? '—' }}</span></div>
            </div>
        </div>

        {{-- Entreprise --}}
        <div class="ic">
            <div class="ic-h">
                <div class="ic-hico" style="background:#eff6ff;color:#2563eb"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg></div>
                <div class="ic-htit">Entreprise</div>
            </div>
            <div class="ic-b">
                <div class="field"><span class="field-l">Nom</span><span class="field-v">{{ $declaration->entreprise->nom ?? '—' }}</span></div>
                <div class="field"><span class="field-l">RCCM</span><span class="field-v">{{ $declaration->entreprise->rccm ?? '—' }}</span></div>
                <div class="field"><span class="field-l">Adresse</span><span class="field-v">{{ $declaration->entreprise->adresse ?? '—' }}</span></div>
                <div class="two" style="gap:.5rem">
                    <div class="field"><span class="field-l">Type</span><span class="field-v">{{ $declaration->entreprise->type_entreprise ?? '—' }}</span></div>
                    <div class="field"><span class="field-l">Secteur</span><span class="field-v">{{ $declaration->entreprise->secteur_activite ?? '—' }}</span></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Activité --}}
    <div class="ic">
        <div class="ic-h">
            <div class="ic-hico" style="background:#ecfdf5;color:#059669"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div>
            <div class="ic-htit">Informations d'activité</div>
        </div>
        <div class="ic-b" style="grid-template-columns:repeat(2,1fr)">
            <div class="field"><span class="field-l">Nature d'activité</span><span class="field-v">{{ $declaration->nature_activite ?? '—' }}</span></div>
            <div class="field"><span class="field-l">Secteur d'activité</span><span class="field-v">{{ $declaration->secteur_activite ?? '—' }}</span></div>
            <div class="field"><span class="field-l">Produits</span><span class="field-v">{{ $declaration->produits ?? '—' }}</span></div>
            <div class="field"><span class="field-l">Effectifs</span><span class="field-v">{{ $declaration->effectifs ?? '—' }}</span></div>
        </div>
    </div>

    {{-- Documents --}}
    <div class="ic">
        <div class="ic-h">
            <div class="ic-hico" style="background:#fef9c3;color:#ca8a04"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8"/></svg></div>
            <div class="ic-htit">Documents soumis</div>
            <span style="margin-left:auto;font-size:.7rem;font-weight:700;background:#eff2ff;color:var(--accent);padding:2px 8px;border-radius:20px;">{{ $declaration->documents->count() }} doc(s)</span>
        </div>

        @if($declaration->documents->isEmpty())
            <div style="text-align:center;padding:2rem;color:var(--t3);font-size:.82rem">Aucun document soumis.</div>
        @else
        <div class="dtbl-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Type de document</th>
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
                    <td style="font-weight:600;color:var(--t1)">{{ $doc->type }}</td>
                    <td>
                        @if($doc->statut === 'validé')
                            <span class="ds-v">Validé</span>
                        @elseif($doc->statut === 'rejeté')
                            <span class="ds-r">Rejeté</span>
                        @else
                            <span class="ds-p">En attente</span>
                        @endif
                    </td>
                    <td>{{ $declaration->entreprise->nom ?? '—' }}</td>
                    <td class="mt">{{ $declaration->nature_activite }}</td>
                    <td class="mt">{{ $declaration->secteur_activite }}</td>
                    <td>
                        <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank" class="view-doc">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            Ouvrir
                        </a>
                    </td>
                    <td x-data="{op:false}">
                        <div class="dpo">
                            <button @click="op=!op" @click.away="op=false" class="dpo-btn">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                            </button>
                            <div class="dpo-menu" x-show="op" x-transition @click.away="op=false">
                                @if($doc->statut !== 'validé')
                                <form action="{{ route('agent.documents.valider', $doc) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dpo-item ok">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                        Valider le document
                                    </button>
                                </form>
                                @endif
                                @if($doc->statut !== 'rejeté')
                                <form action="{{ route('agent.documents.rejeter', $doc) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dpo-item rj">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                        Rejeter le document
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
        @endif

        {{-- Action bar --}}
        <div class="abar">
            <a href="{{ route('agent.dashboard') }}" class="btn-back">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Retour
            </a>
            @if($declaration->statut === 'soumis')
            <form action="{{ route('agent.valider', $declaration) }}" method="POST" style="display:inline">
                @csrf
                <button type="submit" class="btn-ok2">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Valider la déclaration
                </button>
            </form>
            <button type="button" class="btn-rj2" onclick="openRej()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                Rejeter la déclaration
            </button>
            @endif
        </div>
    </div>

</div>

<script>
function openRej() { document.getElementById('moRej').classList.add('op'); setTimeout(()=>document.querySelector('.m-ta').focus(),120); }
function closeRej() { document.getElementById('moRej').classList.remove('op'); }
document.getElementById('moRej').addEventListener('click', e => { if(e.target===e.currentTarget) closeRej(); });
document.addEventListener('keydown', e => { if(e.key==='Escape') closeRej(); });
</script>
</x-agent-layout>