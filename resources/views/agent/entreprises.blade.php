<x-agent-layout>
<x-slot name="pageTitle">Listes des Entreprises</x-slot>

<div class="pg">

    {{-- Header --}}
    <div class="pg-hd">
        <div>
            <div class="pg-title">Mes Entreprises</div>
            <div class="pg-ref">Gérez vos entreprises enregistrées sur la plateforme.</div>
        </div>

        {{-- ── Barre de recherche ── --}}
        <form method="GET" action="{{ route('agent.entreprises') }}" style="display:flex;align-items:center;gap:.5rem;">
            <div style="position:relative;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    style="position:absolute;left:.65rem;top:50%;transform:translateY(-50%);width:14px;height:14px;color:var(--t3);pointer-events:none;">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher une entreprise…"
                    style="padding:.42rem .8rem .42rem 2rem;border:1px solid var(--border);border-radius:8px;
                        font-size:.79rem;font-family:inherit;color:var(--t1);background:var(--white);
                        outline:none;width:240px;transition:border-color .15s;"
                    onfocus="this.style.borderColor='var(--accent)'"
                    onblur="this.style.borderColor='var(--border)'"
                >
            </div>
            <button type="submit"
                    style="padding:.42rem .85rem;border-radius:8px;border:1px solid var(--accent);
                        background:var(--accent);color:#fff;font-size:.79rem;font-weight:600;
                        cursor:pointer;transition:opacity .15s;"
                    onmouseover="this.style.opacity='.85'"
                    onmouseout="this.style.opacity='1'">
                Rechercher
            </button>
            @if(request('search'))
            <a href="{{ route('agent.entreprises') }}"
            style="padding:.42rem .75rem;border-radius:8px;border:1px solid var(--border);
                    background:#f8f9fd;color:var(--t2);font-size:.79rem;font-weight:600;
                    text-decoration:none;transition:all .15s;">
                ✕ Effacer
            </a>
            @endif
        </form>
    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div class="a-ok">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Table --}}
    <div class="card">
        <div class="ch">
            <div class="ct">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;display:inline;vertical-align:middle;margin-right:.35rem"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
                Liste des entreprises
            </div>
            <span class="cc">{{ $entreprises->count() }} entreprise(s)</span>
        </div>
        <div class="tw">
            <table>
                <thead>
                    <tr>
                        <th>Entreprise</th>
                        <th>Gérant</th>
                        <th>RCCM</th>
                        <th>Adresse</th>
                        <th>Type</th>
                        <th>Secteur</th>
                        {{-- <th>Action</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach($entreprises as $entreprise)
                    <tr>
                        <td class="nm">{{ $entreprise->nom }}</td>
                        <td class="nm">{{ $entreprise->gerant->nom }} {{ $entreprise->gerant->prenoms }}</td>
                        <td class="ref">{{ $entreprise->rccm }}</td>
                        <td>{{ $entreprise->adresse }}</td>
                        <td>
                            <span class="bx b-soumis">{{ $entreprise->type_entreprise }}</span>
                        </td>
                        <td>{{ $entreprise->secteur_activite }}</td>
                        {{-- <td>
                            <div class="acts">
                                <a href="{{ route('entreprises.edit', $entreprise) }}" class="bi bi-eye" title="Modifier">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </a>
                                <form action="{{ route('entreprises.destroy', $entreprise) }}" method="POST" onsubmit="return confirm('Supprimer cette entreprise ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bi bi-rej" title="Supprimer">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td> --}}
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
</x-agent-layout>