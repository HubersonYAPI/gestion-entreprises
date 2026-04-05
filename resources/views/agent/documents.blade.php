<x-agent-layout>
<x-slot name="pageTitle">Documents</x-slot>
<style>
:root{--white:#fff;--bg:#f4f6fb;--accent:#2f54eb;--border:#e4e8f0;--t1:#111827;--t2:#4b5563;--t3:#9ca3af;--sh:0 4px 16px rgba(0,0,0,.07);--sh-sm:0 1px 3px rgba(0,0,0,.06);--r:10px;}
.pg{display:flex;flex-direction:column;gap:1.25rem;}

/* Header */
.pg-hd{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;}
.pg-title{font-size:1.15rem;font-weight:700;color:var(--t1);}
.pg-ref{font-size:.77rem;color:var(--t3);margin-top:.15rem;}
.pg-back{display:inline-flex;align-items:center;gap:.4rem;font-size:.77rem;font-weight:600;color:var(--t2);text-decoration:none;padding:.34rem .75rem;border-radius:7px;border:1px solid var(--border);background:var(--white);transition:all .15s;}
.pg-back:hover{background:#eaedfa;color:var(--accent);border-color:#c7d0f5;}
.pg-back svg{width:13px;height:13px;}

/* Alert */
.a-ok{display:flex;align-items:center;gap:.6rem;padding:.7rem 1rem;background:#ecfdf5;border:1px solid #a7f3d0;border-radius:9px;color:#065f46;font-size:.79rem;font-weight:600;}
.a-ok svg{width:14px;height:14px;flex-shrink:0;}

/* Info card */
.ic{background:var(--white);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh-sm);overflow:hidden;}
.ic-h{display:flex;align-items:center;gap:.6rem;padding:.8rem 1.1rem;border-bottom:1px solid var(--border);}
.ic-hico{width:28px;height:28px;border-radius:7px;display:flex;align-items:center;justify-content:center;}
.ic-hico svg{width:14px;height:14px;}
.ic-htit{font-size:.82rem;font-weight:700;color:var(--t1);}
.ic-b{padding:.9rem 1.1rem;}

/* Info grid */
.igrid{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:.75rem;}
.field{display:flex;flex-direction:column;gap:.1rem;}
.field-l{font-size:.66rem;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:var(--t3);}
.field-v{font-size:.82rem;font-weight:500;color:var(--t1);}

/* Stats mini */
.stats-mini{display:flex;gap:.75rem;flex-wrap:wrap;}
.sm{display:flex;align-items:center;gap:.5rem;padding:.45rem .85rem;border-radius:9px;border:1px solid var(--border);background:var(--white);box-shadow:var(--sh-sm);}
.sm-ico{width:24px;height:24px;border-radius:6px;display:flex;align-items:center;justify-content:center;}
.sm-ico svg{width:12px;height:12px;}
.sm-val{font-size:.95rem;font-weight:800;color:var(--t1);}
.sm-lbl{font-size:.7rem;color:var(--t3);}

/* Table */
.tw{overflow-x:auto;}
table{width:100%;border-collapse:collapse;font-size:.79rem;}
th{text-align:left;padding:.55rem 1rem;font-size:.62rem;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:var(--t3);background:#f8f9fd;border-bottom:1px solid var(--border);white-space:nowrap;}
td{padding:.72rem 1rem;color:var(--t2);border-bottom:1px solid var(--border);vertical-align:middle;}
tr:last-child td{border-bottom:none;}
tbody tr:hover td{background:#f8f9fd;}
.nm{font-weight:600;color:var(--t1);}
.mt{color:var(--t3);font-size:.75rem;}

/* Doc status */
.ds-v{display:inline-flex;align-items:center;gap:.28rem;font-size:.67rem;font-weight:700;padding:2px 8px;border-radius:20px;background:#d1fae5;color:#065f46;}
.ds-r{display:inline-flex;align-items:center;gap:.28rem;font-size:.67rem;font-weight:700;padding:2px 8px;border-radius:20px;background:#fee2e2;color:#991b1b;}
.ds-p{display:inline-flex;align-items:center;gap:.28rem;font-size:.67rem;font-weight:700;padding:2px 8px;border-radius:20px;background:#fef9c3;color:#92400e;}
.ds-v::before,.ds-r::before,.ds-p::before{content:'';width:4px;height:4px;border-radius:50%;background:currentColor;}

/* View link */
.vl{display:inline-flex;align-items:center;gap:.33rem;font-size:.76rem;font-weight:600;color:var(--accent);text-decoration:none;transition:opacity .15s;}
.vl:hover{opacity:.7;} .vl svg{width:13px;height:13px;}

/* Dropdown */
.dpo{position:relative;}
.dpo-btn{width:28px;height:28px;border-radius:6px;background:#f8f9fd;border:1px solid var(--border);display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--t3);transition:all .15s;}
.dpo-btn:hover{background:#eaedfa;color:var(--t1);}
.dpo-btn svg{width:14px;height:14px;}
.dpo-menu{position:absolute;right:0;top:calc(100%+4px);min-width:168px;background:var(--white);border:1px solid var(--border);border-radius:9px;padding:.3rem;box-shadow:0 8px 30px rgba(0,0,0,.12);z-index:50;}
.dpo-item{display:flex;align-items:center;gap:.5rem;padding:.42rem .65rem;border-radius:6px;font-size:.78rem;font-weight:500;color:var(--t2);cursor:pointer;border:none;background:none;width:100%;text-align:left;transition:all .15s;}
.dpo-item:hover{background:var(--bg);}
.dpo-item.ok{color:#059669;} .dpo-item.ok:hover{background:#ecfdf5;}
.dpo-item.rj{color:#dc2626;} .dpo-item.rj:hover{background:#fef2f2;}
.dpo-item svg{width:13px;height:13px;flex-shrink:0;}

/* Progress bar */
.prog-bar{height:6px;background:#e5e7eb;border-radius:99px;overflow:hidden;margin-top:.5rem;}
.prog-fill{height:100%;border-radius:99px;background:linear-gradient(90deg,#2f54eb,#6366f1);transition:width .4s ease;}

/* Action bar */
.abar{display:flex;align-items:center;gap:.6rem;padding:1rem 1.1rem;border-top:1px solid var(--border);background:#fafbff;}
.btn-back{display:inline-flex;align-items:center;gap:.4rem;font-size:.78rem;font-weight:600;padding:.44rem .88rem;border-radius:8px;border:1px solid var(--border);background:var(--white);color:var(--t2);text-decoration:none;transition:all .15s;}
.btn-back:hover{background:var(--bg);} .btn-back svg{width:13px;height:13px;}
.btn-decl{display:inline-flex;align-items:center;gap:.4rem;font-size:.78rem;font-weight:600;padding:.44rem .88rem;border-radius:8px;border:1px solid #c7d0f5;background:#eff2ff;color:var(--accent);text-decoration:none;transition:all .15s;}
.btn-decl:hover{background:#dbe4ff;} .btn-decl svg{width:13px;height:13px;}
</style>

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
        $valides = $declaration->documents->where('statut','validé')->count();
        $rejetes = $declaration->documents->where('statut','rejeté')->count();
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
                        @if($doc->statut === 'validé')   <span class="ds-v">Validé</span>
                        @elseif($doc->statut === 'rejeté') <span class="ds-r">Rejeté</span>
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
                                @if($doc->statut !== 'validé')
                                <form action="{{ route('agent.documents.valider', $doc) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dpo-item ok">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                        Valider
                                    </button>
                                </form>
                                @endif
                                @if($doc->statut !== 'rejeté')
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