<x-agent-layout>
<x-slot name="pageTitle">Détail déclaration</x-slot>
<style>
:root{--white:#fff;--bg:#f4f6fb;--accent:#2f54eb;--border:#e4e8f0;--t1:#111827;--t2:#4b5563;--t3:#9ca3af;--sh:0 4px 16px rgba(0,0,0,.07);--sh-sm:0 1px 3px rgba(0,0,0,.06);--r:10px;}
.pg{display:flex;flex-direction:column;gap:1.25rem;}

/* Header */
.pg-hd{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;}
.pg-back{display:inline-flex;align-items:center;gap:.4rem;font-size:.78rem;font-weight:600;color:var(--t2);text-decoration:none;padding:.35rem .75rem;border-radius:7px;border:1px solid var(--border);background:var(--white);transition:all .15s;}
.pg-back:hover{background:#eaedfa;color:var(--accent);border-color:#c7d0f5;}
.pg-back svg{width:13px;height:13px;}
.pg-title{font-size:1.15rem;font-weight:700;color:var(--t1);}
.pg-ref{font-size:.78rem;color:var(--t3);margin-top:.15rem;}

/* Alerts */
.a-ok{display:flex;align-items:center;gap:.6rem;padding:.7rem 1rem;background:#ecfdf5;border:1px solid #a7f3d0;border-radius:9px;color:#065f46;font-size:.79rem;font-weight:600;}
.a-ok svg{width:14px;height:14px;flex-shrink:0;}
.a-err{display:flex;align-items:center;gap:.6rem;padding:.7rem 1rem;background:#fef2f2;border:1px solid #fecaca;border-radius:9px;color:#991b1b;font-size:.79rem;font-weight:600;}
.a-err svg{width:14px;height:14px;flex-shrink:0;}

/* Two column layout */
.two{display:grid;grid-template-columns:1fr 1fr;gap:1rem;}
@media(max-width:900px){.two{grid-template-columns:1fr;}}
.three{display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;}
@media(max-width:900px){.three{grid-template-columns:1fr;}}

/* Info card */
.ic{background:var(--white);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh-sm);overflow:hidden;}
.ic-h{display:flex;align-items:center;gap:.6rem;padding:.8rem 1.1rem;border-bottom:1px solid var(--border);}
.ic-hico{width:28px;height:28px;border-radius:7px;display:flex;align-items:center;justify-content:center;}
.ic-hico svg{width:14px;height:14px;}
.ic-htit{font-size:.82rem;font-weight:700;color:var(--t1);}
.ic-b{padding:.9rem 1.1rem;display:grid;gap:.5rem;}
.field{display:flex;flex-direction:column;gap:.1rem;}
.field-l{font-size:.68rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--t3);}
.field-v{font-size:.82rem;font-weight:500;color:var(--t1);}

/* Status badge */
.bx{display:inline-flex;align-items:center;gap:.3rem;font-size:.68rem;font-weight:700;padding:3px 9px;border-radius:20px;}
.bx::before{content:'';width:5px;height:5px;border-radius:50%;background:currentColor;}
.b-soumis{background:#dbeafe;color:#1d4ed8;} .b-trait{background:#fef9c3;color:#92400e;}
.b-valid{background:#d1fae5;color:#065f46;}  .b-rej{background:#fee2e2;color:#991b1b;}
.b-np{background:#ffe4e6;color:#9f1239;}     .b-def{background:#f1f5f9;color:#64748b;}
.ph{display:inline-flex;align-items:center;font-size:.68rem;font-weight:600;padding:2px 7px;border-radius:6px;background:#f1f5f9;color:var(--t2);border:1px solid var(--border);}

/* Documents table */
.dtbl-wrap{overflow-x:auto;}
table{width:100%;border-collapse:collapse;font-size:.79rem;}
th{text-align:left;padding:.55rem 1rem;font-size:.62rem;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:var(--t3);background:#f8f9fd;border-bottom:1px solid var(--border);white-space:nowrap;}
td{padding:.7rem 1rem;color:var(--t2);border-bottom:1px solid var(--border);vertical-align:middle;}
tr:last-child td{border-bottom:none;}
tbody tr:hover td{background:#f8f9fd;}

/* Doc status */
.ds-v{display:inline-flex;align-items:center;gap:.3rem;font-size:.67rem;font-weight:700;padding:2px 8px;border-radius:20px;background:#d1fae5;color:#065f46;}
.ds-r{display:inline-flex;align-items:center;gap:.3rem;font-size:.67rem;font-weight:700;padding:2px 8px;border-radius:20px;background:#fee2e2;color:#991b1b;}
.ds-p{display:inline-flex;align-items:center;gap:.3rem;font-size:.67rem;font-weight:700;padding:2px 8px;border-radius:20px;background:#fef9c3;color:#92400e;}
.ds-p::before,.ds-v::before,.ds-r::before{content:'';width:4px;height:4px;border-radius:50%;background:currentColor;}

/* Voir doc link */
.view-doc{display:inline-flex;align-items:center;gap:.35rem;font-size:.76rem;font-weight:600;color:var(--accent);text-decoration:none;transition:opacity .15s;}
.view-doc:hover{opacity:.75;}
.view-doc svg{width:13px;height:13px;}

/* Dropdown menu */
.dpo{position:relative;}
.dpo-btn{width:28px;height:28px;border-radius:6px;background:#f8f9fd;border:1px solid var(--border);display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--t3);transition:all .15s;}
.dpo-btn:hover{background:#eaedfa;color:var(--t1);}
.dpo-btn svg{width:14px;height:14px;}
.dpo-menu{position:absolute;right:0;top:calc(100% + 4px);min-width:165px;background:var(--white);border:1px solid var(--border);border-radius:9px;padding:.3rem;box-shadow:0 8px 30px rgba(0,0,0,.12);z-index:50;}
.dpo-item{display:flex;align-items:center;gap:.5rem;padding:.42rem .65rem;border-radius:6px;font-size:.78rem;font-weight:500;color:var(--t2);cursor:pointer;border:none;background:none;width:100%;text-align:left;transition:all .15s;}
.dpo-item:hover{background:var(--bg);color:var(--t1);}
.dpo-item svg{width:13px;height:13px;flex-shrink:0;}
.dpo-item.ok{color:#059669;} .dpo-item.ok:hover{background:#ecfdf5;}
.dpo-item.rj{color:#dc2626;} .dpo-item.rj:hover{background:#fef2f2;}
.dpo-sep{height:1px;background:var(--border);margin:.25rem 0;}

/* Action bar */
.abar{display:flex;align-items:center;gap:.65rem;flex-wrap:wrap;padding:1rem 1.1rem;border-top:1px solid var(--border);background:#fafbff;}
.btn-back{display:inline-flex;align-items:center;gap:.4rem;font-size:.79rem;font-weight:600;padding:.46rem .9rem;border-radius:8px;border:1px solid var(--border);background:var(--white);color:var(--t2);cursor:pointer;text-decoration:none;transition:all .15s;}
.btn-back:hover{background:var(--bg);}
.btn-back svg{width:13px;height:13px;}
.btn-ok2{display:inline-flex;align-items:center;gap:.4rem;font-size:.79rem;font-weight:700;padding:.46rem .9rem;border-radius:8px;border:1px solid #a7f3d0;background:#ecfdf5;color:#059669;cursor:pointer;transition:all .15s;}
.btn-ok2:hover{background:#d1fae5;} .btn-ok2 svg{width:13px;height:13px;}
.btn-rj2{display:inline-flex;align-items:center;gap:.4rem;font-size:.79rem;font-weight:700;padding:.46rem .9rem;border-radius:8px;border:1px solid #fecaca;background:#fef2f2;color:#dc2626;cursor:pointer;transition:all .15s;}
.btn-rj2:hover{background:#fee2e2;} .btn-rj2 svg{width:13px;height:13px;}

/* Modal rejet */
.mo{display:none;position:fixed;inset:0;background:rgba(15,20,45,.45);backdrop-filter:blur(3px);z-index:1000;align-items:center;justify-content:center;padding:1rem;}
.mo.op{display:flex;}
.modal{background:var(--white);border:1px solid var(--border);border-radius:14px;width:100%;max-width:430px;box-shadow:0 24px 60px rgba(0,0,0,.18);animation:mi .2s ease;}
@keyframes mi{from{opacity:0;transform:scale(.95) translateY(8px)}to{opacity:1;transform:scale(1) translateY(0)}}
.mh{display:flex;align-items:center;justify-content:space-between;padding:1rem 1.2rem .85rem;border-bottom:1px solid var(--border);}
.mhl{display:flex;align-items:center;gap:.6rem;}
.m-ico{width:32px;height:32px;border-radius:8px;background:#fef2f2;color:#dc2626;display:flex;align-items:center;justify-content:center;}
.m-ico svg{width:15px;height:15px;}
.m-tit{font-size:.88rem;font-weight:800;color:var(--t1);}
.m-cls{width:26px;height:26px;border-radius:6px;display:flex;align-items:center;justify-content:center;border:1px solid var(--border);background:#f8f9fd;color:var(--t3);cursor:pointer;transition:all .15s;}
.m-cls:hover{background:#fee2e2;color:#dc2626;border-color:#fecaca;} .m-cls svg{width:12px;height:12px;}
.mbody{padding:1rem 1.2rem;display:flex;flex-direction:column;gap:.5rem;}
.m-lbl{font-size:.76rem;font-weight:700;color:var(--t2);margin-bottom:.2rem;display:block;}
.m-ta{width:100%;padding:.6rem .8rem;border:1px solid var(--border);border-radius:8px;font-size:.8rem;font-family:inherit;background:#f8f9fd;color:var(--t1);resize:vertical;min-height:86px;outline:none;transition:border-color .15s;}
.m-ta:focus{border-color:#dc2626;box-shadow:0 0 0 3px rgba(220,38,38,.1);}
.m-hint{font-size:.69rem;color:var(--t3);}
.mfot{display:flex;gap:.5rem;justify-content:flex-end;padding:.85rem 1.2rem 1rem;border-top:1px solid var(--border);}
.btn-cn{font-size:.78rem;font-weight:600;padding:.45rem .9rem;border-radius:8px;border:1px solid var(--border);background:#f8f9fd;color:var(--t2);cursor:pointer;transition:all .15s;}
.btn-cn:hover{background:#eaedfa;}
.btn-rjc{font-size:.78rem;font-weight:700;padding:.45rem 1rem;border-radius:8px;border:none;background:#dc2626;color:#fff;cursor:pointer;transition:all .15s;display:flex;align-items:center;gap:.3rem;}
.btn-rjc:hover{background:#b91c1c;} .btn-rjc svg{width:13px;height:13px;}
</style>

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
            <div class="pg-ref">Soumise le {{ $declaration->created_at->format('d/m/Y à H:i') }}</div>
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
                    $sm=['soumis'=>['Soumis','b-soumis'],'non_paye'=>['Non payé','b-np'],'en_traitement'=>['En traitement','b-trait'],'valide'=>['Validée','b-valid'],'rejete'=>['Rejetée','b-rej']];
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