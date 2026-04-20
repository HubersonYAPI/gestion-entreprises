<x-app-layout>
@include('components.ui-styles')

<div class="db" style="width:100%;overflow-x:hidden;">

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
    <div class="ua-ok">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="ua-err">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        {{ session('error') }}
    </div>
    @endif

    {{-- ── Stat cards ── --}}
    @php
        $total    = $counts['total'];
        $brou     = $counts['brou'];
        $soumis   = $counts['soumis'];
        $approuve = $counts['approuve'];
        $paye     = $counts['paye'];
        $enTrait  = $counts['enTrait'];
        $valide   = $counts['valide'];
        $rejete   = $counts['rejete'];
    @endphp
    <div class="stats">
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg></div>
                <span class="sc-badge neu">Total</span>
            </div>
            <div class="sc-val">{{ $total }}</div>
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
            <div class="sc-val">{{ $soumis }}</div>
            <div class="sc-lbl">Soumises</div>
        </div>
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></div>
                <span class="sc-badge up">✓</span>
            </div>
            <div class="sc-val">{{ $approuve }}</div>
            <div class="sc-lbl">Approuvées</div>
        </div>
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-orange"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg></div>
                <span class="sc-badge neu">Soldées</span>
            </div>
            <div class="sc-val">{{ $paye }}</div>
            <div class="sc-lbl">Soldées</div>
        </div>
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div>
                <span class="sc-badge neu">Cours</span>
            </div>
            <div class="sc-val">{{ $enTrait }}</div>
            <div class="sc-lbl">En traitement</div>
        </div>
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-purple"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg></div>
                <span class="sc-badge" style="background:#ede9fe;color:#5b21b6">✓</span>
            </div>
            <div class="sc-val">{{ $valide }}</div>
            <div class="sc-lbl">Validées</div>
        </div>
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-red"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></div>
                <span class="sc-badge dn">✗</span>
            </div>
            <div class="sc-val">{{ $rejete }}</div>
            <div class="sc-lbl">Rejetées</div>
        </div>
    </div>

    {{-- ── Filtres ── --}}
    <div class="filters">
        <a href="{{ route('declarations.index') }}"
           class="flt {{ !request('statut') ? 'on' : '' }}">Toutes</a>
        <a href="{{ route('declarations.index', ['statut'=>'brouillon']) }}"
           class="flt {{ request('statut')==='brouillon' ? 'on' : '' }}">Brouillons</a>
        <a href="{{ route('declarations.index', ['statut'=>'soumis']) }}"
           class="flt {{ request('statut')==='soumis' ? 'on' : '' }}">Soumises</a>
        <a href="{{ route('declarations.index', ['statut'=>'approuve']) }}"
           class="flt {{ request('statut')==='approuve' ? 'on' : '' }}">Approuvées</a>
        <a href="{{ route('declarations.index', ['statut'=>'paye']) }}"
           class="flt {{ request('statut')==='paye' ? 'on' : '' }}">Soldées</a>
        <a href="{{ route('declarations.index', ['statut'=>'en_traitement']) }}"
           class="flt {{ request('statut')==='en_traitement' ? 'on' : '' }}">En traitement</a>
        <a href="{{ route('declarations.index', ['statut'=>'valide']) }}"
           class="flt {{ request('statut')==='valide' ? 'on' : '' }}">Validées</a>
        <a href="{{ route('declarations.index', ['statut'=>'rejete']) }}"
           class="flt {{ request('statut')==='rejete' ? 'on' : '' }}">Rejetées</a>
    </div>

    {{-- ── Table ── --}}
    <div class="ucard">
        <div class="ucard-header">
            <div class="ucard-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                    <path d="M14 2v6h6"/>
                </svg>
                Liste des déclarations
            </div>
            <span style="font-size:.72rem;font-weight:700;background:var(--acc-bg);color:var(--acc-txt);padding:2px 8px;border-radius:20px;">
                {{ $declarations->count() }} déclaration(s)
            </span>
        </div>

        <div class="utbl-wrap">
            <table class="utbl">
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Entreprise</th>
                        <th>Statut</th>
                        <th>Phase</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($declarations as $declaration)
                    @php
                        $sMap = [
                            'brouillon'     => ['Brouillon',      'ub-indigo'],
                            'soumis'        => ['Soumise',        'ub-blue'],
                            'approuve'      => ['Approuvée',      'ub-green'],
                            'paye'          => ['Soldées',        'ub-teal'],
                            'en_traitement' => ['En traitement',  'ub-yellow'],
                            'valide'        => ['Validée',        'ub-purple'],
                            'rejete'        => ['Rejetée',        'ub-red'],
                        ];
                        [$sl, $sc] = $sMap[$declaration->statut] ?? [ucfirst($declaration->statut), 'ub-gray'];
                    @endphp
                    <tr>
                        <td class="utbl-mono">{{ $declaration->reference }}</td>
                        <td class="utbl-nm">{{ $declaration->entreprise->nom ?? '—' }}</td>
                        <td><span class="ubadge {{ $sc }}">{{ $sl }}</span></td>
                        <td>
                            <span style="font-size:.7rem;font-weight:600;padding:2px 7px;border-radius:6px;background:#f1f5f9;color:var(--t2);border:1px solid var(--border);">
                                {{ $declaration->phase_label }}
                            </span>
                        </td>
                        <td style="font-size:.74rem;color:var(--t3);white-space:nowrap;">
                            {{ $declaration->updated_at->format('d/m/Y') }}
                        </td>

                        {{-- ── Actions dropdown ── --}}
                        <td>
                            <div class="act-wrap">
                                <button class="act-btn" type="button">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="5" cy="12" r="1.2"/>
                                        <circle cx="12" cy="12" r="1.2"/>
                                        <circle cx="19" cy="12" r="1.2"/>
                                    </svg>
                                    Actions
                                    <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <path d="M6 9l6 6 6-6"/>
                                    </svg>
                                </button>

                                <div class="act-menu">

                                    {{-- Voir — toujours présent --}}
                                    <a href="{{ route('declarations.show', $declaration) }}" class="act-item c-view">
                                        <span class="act-ico">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                        </span>
                                        Voir le détail
                                    </a>

                                    @if($declaration->statut === 'brouillon')

                                        <a href="{{ route('documents.index', $declaration) }}" class="act-item c-doc">
                                            <span class="act-ico">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                                    <path d="M14 2v6h6M16 13H8M16 17H8"/>
                                                </svg>
                                            </span>
                                            Documents
                                        </a>

                                        <a href="{{ route('declarations.edit', $declaration) }}" class="act-item c-edit">
                                            <span class="act-ico">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                </svg>
                                            </span>
                                            Modifier
                                        </a>

                                        <div class="act-sep"></div>

                                        <form method="POST" action="{{ route('declarations.submit', $declaration) }}" style="display:contents">
                                            @csrf
                                            <button type="submit" class="act-item c-submit"
                                                    onclick="return confirm('Soumettre cette déclaration ?')">
                                                <span class="act-ico">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <line x1="22" y1="2" x2="11" y2="13"/>
                                                        <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                                                    </svg>
                                                </span>
                                                Soumettre
                                            </button>
                                        </form>

                                        <div class="act-sep"></div>

                                        <form method="POST" action="{{ route('declarations.destroy', $declaration) }}"
                                              style="display:contents"
                                              onsubmit="return confirm('Supprimer cette déclaration ?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="act-item c-del">
                                                <span class="act-ico">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <polyline points="3 6 5 6 21 6"/>
                                                        <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                                                        <path d="M10 11v6M14 11v6"/>
                                                    </svg>
                                                </span>
                                                Supprimer
                                            </button>
                                        </form>

                                    @elseif($declaration->statut === 'rejete')

                                        <a href="{{ route('documents.index', $declaration) }}" class="act-item c-doc">
                                            <span class="act-ico">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                                    <path d="M14 2v6h6M16 13H8M16 17H8"/>
                                                </svg>
                                            </span>
                                            Voir les documents
                                        </a>

                                    @elseif($declaration->statut === 'valide')

                                        <a href="{{ route('paiement.show', $declaration) }}" class="act-item c-pay">
                                            <span class="act-ico">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <rect x="2" y="5" width="20" height="14" rx="2"/>
                                                    <line x1="2" y1="10" x2="22" y2="10"/>
                                                    <circle cx="7" cy="15" r="1"/>
                                                    <circle cx="11" cy="15" r="1"/>
                                                </svg>
                                            </span>
                                            Procéder au paiement
                                        </a>

                                    @elseif($declaration->statut === 'terminé' && !empty($declaration->attestation?->file_path))

                                        <a href="{{ asset('storage/'.$declaration->attestation->file_path) }}"
                                           target="_blank" class="act-item c-dl">
                                            <span class="act-ico">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M12 3v12"/>
                                                    <polyline points="7 10 12 15 17 10"/>
                                                    <path d="M5 21h14"/>
                                                </svg>
                                            </span>
                                            Télécharger attestation
                                        </a>

                                    @endif

                                </div>{{-- /.act-menu --}}
                            </div>{{-- /.act-wrap --}}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="uempty">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                    <path d="M14 2v6h6"/>
                                </svg>
                                Aucune déclaration trouvée.
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- ── Pagination ── --}}
        @if($declarations->hasPages())
            <div style="padding:.7rem 1.2rem;border-top:1px solid var(--border);">
                {{ $declarations->links() }}
            </div>
        @endif

    </div>{{-- /.ucard --}}

</div>

<script>
document.addEventListener('click', function (e) {
    const wrap = e.target.closest('.act-wrap');
    document.querySelectorAll('.act-wrap.open').forEach(el => {
        if (el !== wrap) el.classList.remove('open');
    });
    if (wrap) wrap.classList.toggle('open');
});
</script>

</x-app-layout>