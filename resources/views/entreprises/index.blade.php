<x-app-layout>
@include('components.ui-styles')
<div class="upg upg-wide">

    {{-- Header --}}
    <div class="upg-hd">
        <div>
            <div class="upg-title">Mes Entreprises</div>
            <div class="upg-sub">Gérez vos entreprises enregistrées sur la plateforme.</div>
        </div>
        <a href="{{ route('entreprises.create') }}" class="ubtn ubtn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Ajouter une entreprise
        </a>
    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div class="ua-ok">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Table --}}
    <div class="ucard">
        <div class="ucard-header">
            <div class="ucard-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
                Liste des entreprises
            </div>
            <span style="font-size:.72rem;font-weight:700;background:var(--acc-bg);color:var(--acc-txt);padding:2px 8px;border-radius:20px;">
                {{ $entreprises->count() }} entreprise(s)
            </span>
        </div>
        <div class="utbl-wrap">
            <table class="utbl">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>RCCM</th>
                        <th>Adresse</th>
                        <th>Type</th>
                        <th>Secteur</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entreprises as $entreprise)
                    <tr>
                        <td class="utbl-nm">{{ $entreprise->nom }}</td>
                        <td class="utbl-mono">{{ $entreprise->rccm }}</td>
                        <td>{{ $entreprise->adresse }}</td>
                        <td>
                            <span class="ubadge ub-blue">{{ $entreprise->type_entreprise }}</span>
                        </td>
                        <td>{{ $entreprise->secteur_activite }}</td>
                        <td>
                            <div class="uactions">
                                <a href="{{ route('entreprises.edit', $entreprise) }}" class="uib uib-edit" title="Modifier">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </a>
                                <form action="{{ route('entreprises.destroy', $entreprise) }}" method="POST" onsubmit="return confirm('Supprimer cette entreprise ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="uib uib-del" title="Supprimer">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6">
                        <div class="uempty">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
                            Aucune entreprise enregistrée.<br>
                            <a href="{{ route('entreprises.create') }}" class="ubtn ubtn-primary ubtn-sm" style="margin-top:.75rem;display:inline-flex">Ajouter</a>
                        </div>
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
</x-app-layout>