<x-agent-layout>
<x-slot name="pageTitle">Dashboard</x-slot>
<style>
/* ─── Shared tokens ─── */
:root{--white:#fff;--bg:#f4f6fb;--accent:#2f54eb;--border:#e4e8f0;--t1:#111827;--t2:#4b5563;--t3:#9ca3af;--sh:0 4px 16px rgba(0,0,0,.07);--sh-sm:0 1px 3px rgba(0,0,0,.06);--r:10px;}

/* ─── Layout ─── */
.db{display:flex;flex-direction:column;gap:1.5rem;}

/* ─── Page header ─── */
.db-hd{display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:1rem;}
.db-title{font-size:1.3rem;font-weight:700;color:var(--t1);letter-spacing:-.02em;}
.db-sub{font-size:.79rem;color:var(--t3);margin-top:.15rem;}
.db-date{font-size:.75rem;color:var(--t2);background:var(--white);border:1px solid var(--border);padding:.35rem .8rem;border-radius:8px;box-shadow:var(--sh-sm);}

/* ─── Stat cards ─── */
.stats{display:grid;grid-template-columns:repeat(auto-fill,minmax(185px,1fr));gap:1rem;}
.sc{background:var(--white);border:1px solid var(--border);border-radius:var(--r);padding:1.1rem 1.15rem;box-shadow:var(--sh-sm);display:flex;flex-direction:column;gap:.7rem;transition:box-shadow .2s,transform .2s;}
.sc:hover{box-shadow:var(--sh);transform:translateY(-1px);}
.sc-top{display:flex;align-items:center;justify-content:space-between;}
.sc-ico{width:36px;height:36px;border-radius:9px;display:flex;align-items:center;justify-content:center;}
.sc-ico svg{width:16px;height:16px;}
.sc-badge{font-size:.67rem;font-weight:700;padding:2px 7px;border-radius:20px;}
.up{background:#dcfce7;color:#16a34a;} .dn{background:#fee2e2;color:#dc2626;} .neu{background:#f1f5f9;color:#64748b;}
.sc-val{font-size:1.65rem;font-weight:800;color:var(--t1);letter-spacing:-.03em;line-height:1;}
.sc-lbl{font-size:.73rem;color:var(--t3);font-weight:500;}

.ic-blue  {background:#eff6ff;color:#2563eb;} .ic-green {background:#ecfdf5;color:#059669;}
.ic-amber {background:#fffbeb;color:#d97706;} .ic-red   {background:#fef2f2;color:#dc2626;}
.ic-violet{background:#f5f3ff;color:#7c3aed;} .ic-teal  {background:#f0fdfa;color:#0f766e;}

/* ─── Filters ─── */
.filters{display:flex;align-items:center;gap:.45rem;flex-wrap:wrap;}
.flt{font-size:.74rem;font-weight:600;padding:.32rem .8rem;border-radius:20px;border:1px solid var(--border);background:var(--white);color:var(--t2);cursor:pointer;text-decoration:none;transition:all .15s;white-space:nowrap;}
.flt:hover{background:#eaedfa;color:var(--accent);border-color:#c7d0f5;}
.flt.on{background:var(--accent);color:#fff;border-color:var(--accent);}

/* ─── Card ─── */
.card{background:var(--white);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh-sm);overflow:hidden;}
.ch{display:flex;align-items:center;justify-content:space-between;padding:.9rem 1.2rem;border-bottom:1px solid var(--border);}
.ct{font-size:.85rem;font-weight:700;color:var(--t1);}
.cc{font-size:.7rem;font-weight:700;background:#eff2ff;color:var(--accent);padding:2px 8px;border-radius:20px;}

/* ─── Table ─── */
.tw{overflow-x:auto;}
table{width:100%;border-collapse:collapse;font-size:.79rem;}
th{text-align:left;padding:.55rem 1.1rem;font-size:.63rem;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:var(--t3);background:#f8f9fd;border-bottom:1px solid var(--border);white-space:nowrap;}
td{padding:.72rem 1.1rem;color:var(--t2);border-bottom:1px solid var(--border);vertical-align:middle;}
tr:last-child td{border-bottom:none;}
tbody tr:hover td{background:#f8f9fd;}
.ref{font-family:monospace;font-size:.77rem;font-weight:700;color:var(--t1);}
.nm{font-weight:600;color:var(--t1);max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.mt{color:var(--t3);font-size:.74rem;max-width:130px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}

/* ─── Badges statut ─── */
.bx{display:inline-flex;align-items:center;gap:.3rem;font-size:.67rem;font-weight:700;padding:3px 8px;border-radius:20px;white-space:nowrap;}
.bx::before{content:'';width:5px;height:5px;border-radius:50%;background:currentColor;}
.b-soumis{background:#dbeafe;color:#1d4ed8;} .b-trait{background:#fef9c3;color:#92400e;}
.b-valid{background:#d1fae5;color:#065f46;}  .b-rej{background:#fee2e2;color:#991b1b;}
.b-np{background:#ffe4e6;color:#9f1239;}     .b-def{background:#f1f5f9;color:#64748b;}

.ph{display:inline-flex;align-items:center;font-size:.67rem;font-weight:600;padding:2px 7px;border-radius:6px;background:#f1f5f9;color:var(--t2);border:1px solid var(--border);}

/* ─── Action buttons ─── */
.acts{display:flex;align-items:center;gap:.35rem;}
.bi{width:28px;height:28px;border-radius:6px;display:flex;align-items:center;justify-content:center;border:1px solid var(--border);background:#f8f9fd;color:var(--t2);cursor:pointer;transition:all .15s;text-decoration:none;flex-shrink:0;}
.bi svg{width:13px;height:13px;}
.bi:hover{background:#eaedfa;}
.bi-eye{color:#2563eb;border-color:#bfdbfe;background:#eff6ff;} .bi-eye:hover{background:#dbeafe;}
.bi-doc{color:#7c3aed;border-color:#ddd6fe;background:#f5f3ff;} .bi-doc:hover{background:#ede9fe;}
.bi-ok {color:#059669;border-color:#a7f3d0;background:#ecfdf5;} .bi-ok:hover{background:#d1fae5;}
.bi-rej{color:#dc2626;border-color:#fecaca;background:#fef2f2;} .bi-rej:hover{background:#fee2e2;}

.bv{display:inline-flex;align-items:center;gap:.3rem;font-size:.71rem;font-weight:700;padding:.28rem .65rem;border-radius:6px;border:1px solid #a7f3d0;background:#ecfdf5;color:#059669;cursor:pointer;transition:all .15s;}
.bv:hover{background:#d1fae5;} .bv svg{width:12px;height:12px;}

/* ─── Empty ─── */
.empty{text-align:center;padding:3rem 1rem;color:var(--t3);font-size:.83rem;}
.empty svg{width:38px;height:38px;opacity:.2;margin:0 auto .7rem;display:block;}

/* ─── Pagination ─── */
.pager{padding:.7rem 1.2rem;border-top:1px solid var(--border);}
.pager .pagination{display:flex;gap:.3rem;list-style:none;flex-wrap:wrap;}
.pager .page-item .page-link{display:flex;align-items:center;justify-content:center;min-width:30px;height:30px;padding:0 .45rem;border-radius:6px;border:1px solid var(--border);font-size:.76rem;font-weight:600;color:var(--t2);background:var(--white);text-decoration:none;transition:all .15s;}
.pager .page-item.active .page-link{background:var(--accent);color:#fff;border-color:var(--accent);}
.pager .page-item .page-link:hover{background:#eaedfa;color:var(--accent);}
.pager .page-item.disabled .page-link{opacity:.4;pointer-events:none;}

/* ─── Alert ─── */
.alert-ok{display:flex;align-items:center;gap:.65rem;padding:.75rem 1rem;background:#ecfdf5;border:1px solid #a7f3d0;border-radius:9px;color:#065f46;font-size:.8rem;font-weight:600;}
.alert-ok svg{width:15px;height:15px;flex-shrink:0;}
.alert-err{display:flex;align-items:center;gap:.65rem;padding:.75rem 1rem;background:#fef2f2;border:1px solid #fecaca;border-radius:9px;color:#991b1b;font-size:.8rem;font-weight:600;}
.alert-err svg{width:15px;height:15px;flex-shrink:0;}

/* ─── MODAL ─── */
.mo{display:none;position:fixed;inset:0;background:rgba(15,20,45,.45);backdrop-filter:blur(3px);z-index:1000;align-items:center;justify-content:center;padding:1rem;}
.mo.op{display:flex;}
.modal{background:var(--white);border:1px solid var(--border);border-radius:14px;width:100%;max-width:435px;box-shadow:0 24px 60px rgba(0,0,0,.18);animation:min .2s ease;}
@keyframes min{from{opacity:0;transform:scale(.95) translateY(8px)}to{opacity:1;transform:scale(1) translateY(0)}}
.mh{display:flex;align-items:center;justify-content:space-between;padding:1rem 1.2rem .85rem;border-bottom:1px solid var(--border);}
.mh-l{display:flex;align-items:center;gap:.6rem;}
.m-ico{width:34px;height:34px;border-radius:9px;background:#fef2f2;color:#dc2626;display:flex;align-items:center;justify-content:center;}
.m-ico svg{width:16px;height:16px;}
.m-tit{font-size:.9rem;font-weight:800;color:var(--t1);}
.m-ref{font-size:.74rem;color:var(--t3);margin-top:.1rem;}
.m-cls{width:27px;height:27px;border-radius:6px;display:flex;align-items:center;justify-content:center;border:1px solid var(--border);background:#f8f9fd;color:var(--t3);cursor:pointer;transition:all .15s;}
.m-cls:hover{background:#fee2e2;color:#dc2626;border-color:#fecaca;} .m-cls svg{width:12px;height:12px;}
.mb{padding:1.1rem 1.2rem;display:flex;flex-direction:column;gap:.6rem;}
.m-lbl{font-size:.77rem;font-weight:700;color:var(--t2);margin-bottom:.25rem;display:block;}
.m-ta{width:100%;padding:.6rem .8rem;border:1px solid var(--border);border-radius:8px;font-size:.8rem;font-family:inherit;background:#f8f9fd;color:var(--t1);resize:vertical;min-height:88px;outline:none;transition:border-color .15s;}
.m-ta:focus{border-color:#dc2626;box-shadow:0 0 0 3px rgba(220,38,38,.1);}
.m-hint{font-size:.7rem;color:var(--t3);}
.mf{display:flex;gap:.55rem;justify-content:flex-end;padding:.85rem 1.2rem 1rem;border-top:1px solid var(--border);}
.btn-cancel{font-size:.79rem;font-weight:600;padding:.48rem .95rem;border-radius:8px;border:1px solid var(--border);background:#f8f9fd;color:var(--t2);cursor:pointer;transition:all .15s;}
.btn-cancel:hover{background:#eaedfa;color:var(--t1);}
.btn-rej2{font-size:.79rem;font-weight:700;padding:.48rem 1.05rem;border-radius:8px;border:none;background:#dc2626;color:#fff;cursor:pointer;transition:all .15s;display:flex;align-items:center;gap:.35rem;}
.btn-rej2:hover{background:#b91c1c;} .btn-rej2 svg{width:13px;height:13px;}
</style>

{{-- MODAL REJET --}}
<div class="mo" id="moRej">
    <div class="modal">
        <div class="mh">
            <div class="mh-l">
                <div class="m-ico">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                </div>
                <div>
                    <div class="m-tit">Rejeter la déclaration</div>
                    <div class="m-ref" id="mRef"></div>
                </div>
            </div>
            <button class="m-cls" type="button" onclick="closeRej()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="rejForm" method="POST">
            @csrf
            <div class="mb">
                <div>
                    <label class="m-lbl" for="commentaire">Motif du rejet <span style="color:#dc2626">*</span></label>
                    <textarea class="m-ta" id="commentaire" name="commentaire" placeholder="Décrivez clairement la raison du rejet…" required></textarea>
                    <div class="m-hint">Ce motif sera transmis au demandeur.</div>
                </div>
            </div>
            <div class="mf">
                <button type="button" class="btn-cancel" onclick="closeRej()">Annuler</button>
                <button type="submit" class="btn-rej2">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    Confirmer le rejet
                </button>
            </div>
        </form>
    </div>
</div>

<div class="db">

    {{-- Header --}}
    <div class="db-hd">
        <div>
            <div class="db-title">Déclarations</div>
            <div class="db-sub">Gestion et traitement des déclarations soumises.</div>
        </div>
        <div class="db-date">{{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}</div>
    </div>

    {{-- Alertes --}}
    @if(session('success'))
    <div class="alert-ok">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="alert-err">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        {{ session('error') }}
    </div>
    @endif

    {{-- Stat cards --}}
    <div class="stats">
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg></div>
                <span class="sc-badge neu">Total</span>
            </div>
            <div class="sc-val">{{ $stats['total'] }}</div>
            <div class="sc-lbl">Déclarations</div>
        </div>
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
                <span class="sc-badge neu">En attente</span>
            </div>
            <div class="sc-val">{{ $stats['soumis'] }}</div>
            <div class="sc-lbl">Soumises</div>
        </div>
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></div>
                <span class="sc-badge neu">En cours</span>
            </div>
            <div class="sc-val">{{ $stats['en_traitement'] }}</div>
            <div class="sc-lbl">En traitement</div>
        </div>
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></div>
                <span class="sc-badge up">✓</span>
            </div>
            <div class="sc-val">{{ $stats['valide'] }}</div>
            <div class="sc-lbl">Validées</div>
        </div>
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-red"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></div>
                <span class="sc-badge dn">✗</span>
            </div>
            <div class="sc-val">{{ $stats['rejete'] }}</div>
            <div class="sc-lbl">Rejetées</div>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="filters">
        <a href="{{ route('agent.dashboard') }}" class="flt {{ request()->routeIs('agent.dashboard') ? 'on':'' }}">Toutes</a>
        <a href="{{ route('agent.declarations.soumis') }}" class="flt {{ request()->routeIs('agent.declarations.soumis') ? 'on':'' }}">Soumises</a>
        <a href="{{ route('agent.declarations.non-paye') }}" class="flt {{ request()->routeIs('agent.declarations.non-paye') ? 'on':'' }}">Non payées</a>
        <a href="{{ route('agent.declarations.en-traitement') }}" class="flt {{ request()->routeIs('agent.declarations.en-traitement') ? 'on':'' }}">En traitement</a>
        <a href="{{ route('agent.declarations.valider') }}" class="flt {{ request()->routeIs('agent.declarations.valider') ? 'on':'' }}">Validées</a>
        <a href="{{ route('agent.declarations.rejeter') }}" class="flt {{ request()->routeIs('agent.declarations.rejeter') ? 'on':'' }}">Rejetées</a>
    </div>

    {{-- Table --}}
    <div class="card">
        <div class="ch">
            <span class="ct">Liste des déclarations</span>
            <span class="cc">{{ $declarations->total() }} entrée(s)</span>
        </div>
        <div class="tw">
            <table>
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Entreprise</th>
                        <th>Nature d'activité</th>
                        <th>Secteur</th>
                        <th>Statut</th>
                        <th>Phase</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($declarations as $decl)
                    @php
                        $sm = ['soumis'=>['Soumis','b-soumis'],'non_paye'=>['Non payé','b-np'],'en_traitement'=>['En traitement','b-trait'],'validé'=>['Validée','b-valid'],'rejeté'=>['Rejetée','b-rej']];
                        [$sl,$sc] = $sm[$decl->statut] ?? [ucfirst($decl->statut),'b-def'];
                    @endphp
                    <tr>
                        <td class="ref">{{ $decl->reference }}</td>
                        <td><div class="nm">{{ $decl->entreprise->nom ?? '—' }}</div></td>
                        <td class="mt">{{ $decl->nature_activite ?? '—' }}</td>
                        <td class="mt">{{ $decl->secteur_activite ?? '—' }}</td>
                        <td><span class="bx {{ $sc }}">{{ $sl }}</span></td>
                        <td><span class="ph">{{ $decl->phase_label }}</span></td>
                        <td style="white-space:nowrap;color:var(--t3);font-size:.73rem">{{ $decl->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="acts">
                                <a href="{{ route('agent.declarations.show', $decl) }}" class="bi bi-eye" title="Voir">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>
                                <a href="{{ route('agent.declaration.documents', $decl) }}" class="bi bi-doc" title="Documents">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8"/></svg>
                                </a>
                                @if($decl->statut === 'soumis')
                                    <form action="{{ route('agent.valider', $decl) }}" method="POST" style="display:inline">
                                        @csrf
                                        <button type="submit" class="bv" title="Valider">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                            Valider
                                        </button>
                                    </form>
                                    <button type="button" class="bi bi-rej" title="Rejeter"
                                        onclick="openRej('{{ $decl->reference }}','{{ route('agent.rejeter', $decl) }}')">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8">
                        <div class="empty">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8"/></svg>
                            Aucune déclaration trouvée.
                        </div>
                    </td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($declarations->hasPages())
        <div class="pager">{{ $declarations->links() }}</div>
        @endif
    </div>
</div>

<script>
function openRej(ref, action) {
    document.getElementById('rejForm').action = action;
    document.getElementById('mRef').textContent = ref;
    document.getElementById('commentaire').value = '';
    document.getElementById('moRej').classList.add('op');
    setTimeout(() => document.getElementById('commentaire').focus(), 120);
}
function closeRej() { document.getElementById('moRej').classList.remove('op'); }
document.getElementById('moRej').addEventListener('click', e => { if(e.target===e.currentTarget) closeRej(); });
document.addEventListener('keydown', e => { if(e.key==='Escape') closeRej(); });
</script>
</x-agent-layout>