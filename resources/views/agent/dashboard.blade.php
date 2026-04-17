<x-agent-layout>

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
            <div class="sc-val">{{ $stats['validé'] }}</div>
            <div class="sc-lbl">Validées</div>
        </div>
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-red"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></div>
                <span class="sc-badge dn">✗</span>
            </div>
            <div class="sc-val">{{ $stats['rejeté'] }}</div>
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
                        <td style="white-space:nowrap;color:var(--t3);font-size:.73rem">{{ $decl->updated_at->format('d/m/Y') }}</td>
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

                                {{-- Passer en traitement --}}
                                @if($decl->statut === 'validé')
                                <form method="POST" action="{{ route('agent.traiter', $decl) }}">
                                    @csrf
                                    <button type="submit" class="bm" title="Valider">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                        En traitement
                                    </button>                                    
                                </form>
                                @endif

                                {{-- Terminer --}}
                                @if($decl->statut === 'en_traitement')
                                <form method="POST" action="{{ route('agent.terminer', $decl) }}">
                                    @csrf
                                    <button type="submit" class="bm" title="Valider">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                        Terminer
                                    </button> 
                                </form>
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