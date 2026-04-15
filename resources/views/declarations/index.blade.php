<x-app-layout>
@include('components.ui-styles')
<div class="upg upg-wide">

    {{-- Header --}}
    <div class="upg-hd">
        <div>
            <div class="upg-title">Mes Déclarations</div>
            <div class="upg-sub">Suivez l'état de toutes vos déclarations.</div>
        </div>
        <a href="{{ route('declarations.create') }}" class="ubtn ubtn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nouvelle déclaration
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
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        {{ session('error') }}
    </div>
    @endif

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

    {{-- Table --}}
    <div class="ucard">
        <div class="ucard-header">
            <div class="ucard-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
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
                        $sMap = ['brouillon'=>['Brouillon','ub-gray'],'soumis'=>['Soumis','ub-blue'],'en_traitement'=>['En traitement','ub-yellow'],'validé'=>['Validée','ub-green'],'rejeté'=>['Rejetée','ub-red']];
                        [$sl,$sc] = $sMap[$declaration->statut] ?? [ucfirst($declaration->statut),'ub-gray'];
                    @endphp
                    <tr>
                        <td class="utbl-mono">{{ $declaration->reference }}</td>
                        <td class="utbl-nm">{{ $declaration->entreprise->nom }}</td>
                        <td><span class="ubadge {{ $sc }}">{{ $sl }}</span></td>
                        <td>
                            <span style="font-size:.7rem;font-weight:600;padding:2px 7px;border-radius:6px;background:#f1f5f9;color:var(--t2);border:1px solid var(--border);">
                                {{ $declaration->phase_label }}
                            </span>
                        </td>
                        <td style="font-size:.74rem;color:var(--t3);white-space:nowrap">
                            {{ $declaration->updated_at->format('d/m/Y') }}
                        </td>
                        <td>
                            <div class="uactions">
                                {{-- Voir --}}
                                <a href="{{ route('declarations.show', $declaration) }}" class="uib uib-eye" title="Voir">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>

                                @if($declaration->statut === 'brouillon')
                                    {{-- Documents --}}
                                    <a href="{{ route('documents.index', $declaration) }}" class="uib uib-doc" title="Documents">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8"/></svg>
                                    </a>
                                    {{-- Modifier --}}
                                    <a href="{{ route('declarations.edit', $declaration) }}" class="uib uib-edit" title="Modifier">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    </a>
                                    {{-- Soumettre --}}
                                    <form method="POST" action="{{ route('declarations.submit', $declaration) }}" style="display:inline">
                                        @csrf
                                        <button type="submit" class="uib uib-ok" title="Soumettre" onclick="return confirm('Soumettre cette déclaration ?')">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                        </button>
                                    </form>
                                    {{-- Supprimer --}}
                                    <form method="POST" action="{{ route('declarations.destroy', $declaration) }}" style="display:inline" onsubmit="return confirm('Supprimer cette déclaration ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="uib uib-del" title="Supprimer">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                                        </button>
                                    </form>

                                @elseif($declaration->statut === 'rejeté')
                                    {{-- Voir documents si rejeté --}}
                                    <a href="{{ route('documents.index', $declaration) }}" class="uib uib-doc" title="Documents">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8"/></svg>
                                    </a>

                                @elseif($declaration->statut === 'validé')
                                    {{-- Voir documents si Validé --}}
                                    <a href="{{ route('paiement.show', $declaration) }}" class="uib uib-ok" title="Payer">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2" ry="2"/>
        <line x1="2" y1="10" x2="22" y2="10"/>
        <circle cx="7" cy="15" r="1"/>
        <circle cx="11" cy="15" r="1"/>
    </svg>
</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6">
                        <div class="uempty">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
                            Aucune déclaration pour l'instant.<br>
                            <a href="{{ route('declarations.create') }}" class="ubtn ubtn-primary ubtn-sm" style="margin-top:.75rem;display:inline-flex">Créer</a>
                        </div>
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@if($declarations->hasPages())
        <div class="pager">{{ $declarations->links() }}</div>
        @endif
</div>
</x-app-layout>