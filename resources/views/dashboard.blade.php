<x-app-layout>

<style>
:root { --white:#fff; --bg:#f0f2f8; --accent:#2f54eb; --border:#e4e8f0; --t1:#111827; --t2:#4b5563; --t3:#9ca3af; --sh-sm:0 1px 3px rgba(0,0,0,.06); --sh:0 4px 16px rgba(0,0,0,.07); --r:10px; }

/* Layout */
.db { display:flex; flex-direction:column; gap:1.5rem; }

/* Header */
.db-hd  { display:flex; align-items:flex-end; justify-content:space-between; flex-wrap:wrap; gap:1rem; }
.db-title { font-size:1.3rem; font-weight:800; color:var(--t1); letter-spacing:-.02em; }
.db-sub   { font-size:.79rem; color:var(--t3); margin-top:.15rem; }
.db-date  { font-size:.75rem; color:var(--t2); background:var(--white); border:1px solid var(--border); padding:.35rem .8rem; border-radius:8px; box-shadow:var(--sh-sm); }

/* Stat cards */
.stats { display:grid; grid-template-columns:repeat(auto-fill,minmax(180px,1fr)); gap:1rem; }
.sc { background:var(--white); border:1px solid var(--border); border-radius:var(--r); padding:1.1rem 1.15rem; box-shadow:var(--sh-sm); display:flex; flex-direction:column; gap:.65rem; transition:box-shadow .2s,transform .2s; }
.sc:hover { box-shadow:var(--sh); transform:translateY(-1px); }
.sc-top  { display:flex; align-items:center; justify-content:space-between; }
.sc-ico  { width:36px; height:36px; border-radius:9px; display:flex; align-items:center; justify-content:center; }
.sc-ico svg { width:16px; height:16px; }
.sc-badge { font-size:.67rem; font-weight:700; padding:2px 7px; border-radius:20px; }
.neu{background:#f1f5f9;color:#64748b;} .up{background:#dcfce7;color:#16a34a;} .dn{background:#fee2e2;color:#dc2626;}
.sc-val { font-size:1.65rem; font-weight:800; color:var(--t1); letter-spacing:-.03em; line-height:1; }
.sc-lbl { font-size:.73rem; color:var(--t3); font-weight:500; }
.ic-blue  {background:#eff6ff;color:#2563eb;} .ic-green{background:#ecfdf5;color:#059669;}
.ic-amber {background:#fffbeb;color:#d97706;} .ic-red  {background:#fef2f2;color:#dc2626;}
.ic-purple{background:#f5f3ff;color:#7c3aed;} .ic-orange{background:#fff7ed;color:#c2410c;}
.ic-gray  {background:#f8fafc;color:#475569;}

/* Filters */
.filters { display:flex; align-items:center; gap:.45rem; flex-wrap:wrap; }
.flt { font-size:.74rem; font-weight:600; padding:.32rem .8rem; border-radius:20px; border:1px solid var(--border); background:var(--white); color:var(--t2); cursor:pointer; text-decoration:none; transition:all .15s; white-space:nowrap; }
.flt:hover { background:#eaedfa; color:var(--accent); border-color:#c7d0f5; }
.flt.on { background:var(--accent); color:#fff; border-color:var(--accent); }

/* Card */
.card { background:var(--white); border:1px solid var(--border); border-radius:var(--r); box-shadow:var(--sh-sm); overflow:hidden; }
.ch   { display:flex; align-items:center; justify-content:space-between; padding:.9rem 1.2rem; border-bottom:1px solid var(--border); }
.ct   { font-size:.85rem; font-weight:700; color:var(--t1); }
.cc   { font-size:.7rem; font-weight:700; background:#eff2ff; color:var(--accent); padding:2px 8px; border-radius:20px; }

/* Table */
.tw   { overflow-x:auto; }
table { width:100%; border-collapse:collapse; font-size:.79rem; }
th    { text-align:left; padding:.55rem 1.1rem; font-size:.63rem; font-weight:700; letter-spacing:.07em; text-transform:uppercase; color:var(--t3); background:#f8f9fd; border-bottom:1px solid var(--border); white-space:nowrap; }
td    { padding:.72rem 1.1rem; color:var(--t2); border-bottom:1px solid var(--border); vertical-align:middle; }
tr:last-child td { border-bottom:none; }
tbody tr:hover td { background:#f8f9fd; }
.ref { font-family:monospace; font-size:.78rem; font-weight:700; color:var(--t1); }
.nm  { font-weight:600; color:var(--t1); max-width:160px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
.mt  { color:var(--t3); font-size:.75rem; }
.ph  { display:inline-flex; font-size:.67rem; font-weight:600; padding:2px 7px; border-radius:6px; background:#f1f5f9; color:var(--t2); border:1px solid var(--border); }

/* Badges */
.bx { display:inline-flex; align-items:center; gap:.28rem; font-size:.67rem; font-weight:700; padding:3px 8px; border-radius:20px; white-space:nowrap; }
.bx::before { content:''; width:5px; height:5px; border-radius:50%; background:currentColor; }
.b-brou {background:#f1f5f9;color:#475569;} .b-sou{background:#dbeafe;color:#1d4ed8;}
.b-trt  {background:#fef9c3;color:#92400e;} .b-val{background:#d1fae5;color:#065f46;}
.b-rej  {background:#fee2e2;color:#991b1b;} .b-np {background:#ffedd5;color:#9a3412;}
.b-fin  {background:#ede9fe;color:#5b21b6;} .b-def{background:#f1f5f9;color:#64748b;}

/* Actions */
.acts { display:flex; align-items:center; gap:.35rem; }
.bi   { width:28px; height:28px; border-radius:6px; display:flex; align-items:center; justify-content:center; border:1px solid var(--border); background:#f8f9fd; color:var(--t2); cursor:pointer; transition:all .15s; text-decoration:none; flex-shrink:0; }
.bi svg { width:13px; height:13px; }
.bi:hover  { background:#eaedfa; }
.bi-eye  { color:#2563eb; border-color:#bfdbfe; background:#eff6ff; } .bi-eye:hover  { background:#dbeafe; }
.bi-doc  { color:#7c3aed; border-color:#ddd6fe; background:#f5f3ff; } .bi-doc:hover  { background:#ede9fe; }
.bi-edit { color:#d97706; border-color:#fcd34d; background:#fffbeb; } .bi-edit:hover { background:#fef3c7; }
.bi-del  { color:#dc2626; border-color:#fecaca; background:#fef2f2; } .bi-del:hover  { background:#fee2e2; }
.bv { display:inline-flex; align-items:center; gap:.3rem; font-size:.71rem; font-weight:700; padding:.28rem .65rem; border-radius:6px; border:1px solid #a7f3d0; background:#ecfdf5; color:#059669; cursor:pointer; transition:all .15s; }
.bv:hover { background:#d1fae5; } .bv svg { width:12px; height:12px; }

/* Alerts */
.a-ok  { display:flex; align-items:center; gap:.65rem; padding:.75rem 1rem; background:#ecfdf5; border:1px solid #a7f3d0; border-radius:10px; color:#065f46; font-size:.8rem; font-weight:600; }
.a-err { display:flex; align-items:center; gap:.65rem; padding:.75rem 1rem; background:#fef2f2; border:1px solid #fecaca; border-radius:10px; color:#991b1b; font-size:.8rem; font-weight:600; }
.a-ok svg, .a-err svg { width:15px; height:15px; flex-shrink:0; }

/* Empty */
.empty { text-align:center; padding:3rem 1rem; color:var(--t3); font-size:.83rem; }
.empty svg { width:38px; height:38px; opacity:.2; margin:0 auto .7rem; display:block; }

/* Pagination */
.pager { padding:.7rem 1.2rem; border-top:1px solid var(--border); }
.pager .pagination { display:flex; gap:.3rem; list-style:none; flex-wrap:wrap; }
.pager .page-item .page-link { display:flex; align-items:center; justify-content:center; min-width:30px; height:30px; padding:0 .45rem; border-radius:6px; border:1px solid var(--border); font-size:.76rem; font-weight:600; color:var(--t2); background:var(--white); text-decoration:none; transition:all .15s; }
.pager .page-item.active .page-link { background:var(--accent); color:#fff; border-color:var(--accent); }
.pager .page-item .page-link:hover { background:#eaedfa; color:var(--accent); }
.pager .page-item.disabled .page-link { opacity:.4; pointer-events:none; }
</style>

<div class="db">

    {{-- ── Header ── --}}
    <div class="db-hd">
        <div>
            <div class="db-title">Mes Déclarations</div>
            <div class="db-sub">Suivez l'avancement de toutes vos déclarations.</div>
        </div>
        <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap">
            <div class="db-date">{{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}</div>
            <a href="{{ route('declarations.create') }}"
               style="display:inline-flex;align-items:center;gap:.4rem;font-size:.79rem;font-weight:700;padding:.46rem .95rem;border-radius:8px;background:var(--accent);color:#fff;text-decoration:none;transition:opacity .15s"
               onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
                <svg style="width:13px;height:13px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Nouvelle déclaration
            </a>
        </div>
    </div>

    {{-- ── Alertes ── --}}
    @if(session('success'))
    <div class="a-ok">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="a-err">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        {{ session('error') }}
    </div>
    @endif

    {{-- ── Stat cards ── --}}
    @php
        $all   = $declarations->count();
        $brou  = $declarations->where('statut','brouillon')->count();
        $sou   = $declarations->where('statut','soumis')->count();
        $trt   = $declarations->where('statut','en_traitement')->count();
        $val   = $declarations->where('statut','validé')->count();
        $rej   = $declarations->where('statut','rejeté')->count();
        $np    = $declarations->where('statut','non_paye')->count();
        $fin   = $declarations->where('statut','finalise')->count();
    @endphp
    <div class="stats">
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg></div>
                <span class="sc-badge neu">Total</span>
            </div>
            <div class="sc-val">{{ $all }}</div>
            <div class="sc-lbl">Déclarations</div>
        </div>
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-gray"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></div>
                <span class="sc-badge neu">Brouillon</span>
            </div>
            <div class="sc-val">{{ $brou }}</div>
            <div class="sc-lbl">Brouillons</div>
        </div>
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
                <span class="sc-badge neu">En attente</span>
            </div>
            <div class="sc-val">{{ $sou }}</div>
            <div class="sc-lbl">Soumises</div>
        </div>
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div>
                <span class="sc-badge neu">Cours</span>
            </div>
            <div class="sc-val">{{ $trt }}</div>
            <div class="sc-lbl">En traitement</div>
        </div>
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></div>
                <span class="sc-badge up">✓</span>
            </div>
            <div class="sc-val">{{ $val }}</div>
            <div class="sc-lbl">Validées</div>
        </div>
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-orange"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg></div>
                <span class="sc-badge neu">À payer</span>
            </div>
            <div class="sc-val">{{ $np }}</div>
            <div class="sc-lbl">Att. paiement</div>
        </div>
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-red"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></div>
                <span class="sc-badge dn">✗</span>
            </div>
            <div class="sc-val">{{ $rej }}</div>
            <div class="sc-lbl">Rejetées</div>
        </div>
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-purple"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg></div>
                <span class="sc-badge" style="background:#ede9fe;color:#5b21b6">✓</span>
            </div>
            <div class="sc-val">{{ $fin }}</div>
            <div class="sc-lbl">Finalisées</div>
        </div>
    </div>

    {{-- ── Filtres ── --}}
    <div class="filters">
        <a href="{{ route('declarations.index') }}"
           class="flt {{ !request('statut') ? 'on':'' }}">Toutes</a>
        <a href="{{ route('declarations.index', ['statut'=>'soumis']) }}"
           class="flt {{ request('statut')==='soumis' ? 'on':'' }}">Soumises</a>
        <a href="{{ route('declarations.index', ['statut'=>'validé']) }}"
           class="flt {{ request('statut')==='validé' ? 'on':'' }}">Validées</a>
        <a href="{{ route('declarations.index', ['statut'=>'rejeté']) }}"
           class="flt {{ request('statut')==='rejeté' ? 'on':'' }}">Rejetées</a>
        <a href="{{ route('declarations.index', ['statut'=>'non_paye']) }}"
           class="flt {{ request('statut')==='non_paye' ? 'on':'' }}">Att. paiement</a>
        <a href="{{ route('declarations.index', ['statut'=>'en_traitement']) }}"
           class="flt {{ request('statut')==='en_traitement' ? 'on':'' }}">En traitement</a>
        <a href="{{ route('declarations.index', ['statut'=>'finalise']) }}"
           class="flt {{ request('statut')==='finalise' ? 'on':'' }}">Finalisées</a>
    </div>

    {{-- ── Table ── --}}
    <div class="card">
        <div class="ch">
            <span class="ct">Liste des déclarations</span>
            <span class="cc">{{ $declarations->count() }} entrée(s)</span>
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
                        $sm = [
                            'brouillon'     => ['Brouillon',        'b-brou'],
                            'soumis'        => ['Soumis',           'b-sou'],
                            'en_traitement' => ['En traitement',    'b-trt'],
                            'validé'        => ['Validée',          'b-val'],
                            'rejeté'        => ['Rejetée',          'b-rej'],
                            'non_paye'      => ['Att. paiement',    'b-np'],
                            'finalise'      => ['Finalisée',        'b-fin'],
                        ];
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
                                {{-- Voir --}}
                                <a href="{{ route('declarations.show', $decl) }}" class="bi bi-eye" title="Voir">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>

                                @if($decl->statut === 'brouillon')
                                    {{-- Documents --}}
                                    <a href="{{ route('documents.index', $decl) }}" class="bi bi-doc" title="Documents">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8"/></svg>
                                    </a>
                                    {{-- Modifier --}}
                                    <a href="{{ route('declarations.edit', $decl) }}" class="bi bi-edit" title="Modifier">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    </a>
                                    {{-- Soumettre --}}
                                    <form method="POST" action="{{ route('declarations.submit', $decl) }}" style="display:inline">
                                        @csrf
                                        <button type="submit" class="bv" onclick="return confirm('Soumettre cette déclaration ?')">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                            Soumettre
                                        </button>
                                    </form>
                                    {{-- Supprimer --}}
                                    <form method="POST" action="{{ route('declarations.destroy', $decl) }}" style="display:inline" onsubmit="return confirm('Supprimer ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="bi bi-del" title="Supprimer">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>
                                        </button>
                                    </form>

                                @elseif($decl->statut === 'rejeté')
                                    {{-- Voir documents si rejeté --}}
                                    <a href="{{ route('documents.index', $decl) }}" class="bi bi-doc" title="Documents">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8"/></svg>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8">
                        <div class="empty">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
                            Aucune déclaration trouvée.
                            <br>
                            <a href="{{ route('declarations.create') }}"
                               style="display:inline-flex;align-items:center;gap:.35rem;font-size:.76rem;font-weight:700;padding:.38rem .8rem;border-radius:8px;background:var(--accent);color:#fff;text-decoration:none;margin-top:.75rem">
                                <svg style="width:12px;height:12px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                Créer ma première déclaration
                            </a>
                        </div>
                    </td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
</x-app-layout>