<x-agent-layout>
<x-slot name="pageTitle">Listes des gerants</x-slot>

<div class="pg">

    {{-- Header --}}
    <div class="pg-hd">
        <div>
            {{-- <div class="pg-title">Mes Entreprises</div>
            <div class="pg-ref">Gérez vos entreprises enregistrées sur la plateforme.</div> --}}
        </div>
        <form method="GET" action="{{ route('agent.gerants') }}" style="display:flex;align-items:center;gap:.5rem;">
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
            <a href="{{ route('agent.gerants') }}"
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
                Liste des gérants
            </div>
            <span class="cc">{{ $gerants->count() }} gerant(s)</span>
        </div>
        <div class="tw">
            <table>
                <thead>
                    <tr>
                        <th>Gérant</th>
                        <th>Contact</th>
                        <th>Entreprise</th>
                        <th>RCCM</th>
                        <th>adresse</th>
                        <th>Type</th>
                        <th>Secteur</th>
                        {{-- <th>Action</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach($gerants as $gerant)
                        @foreach($gerant->entreprises as $entreprise)
                            <tr>
                                <td class="nm">{{ $gerant->nom }} {{ $gerant->prenoms }}</td>
                                <td class="nm">{{ $gerant->contact }}</td>

                                <td class="ref">{{ $entreprise->nom }}</td>
                                <td>{{ $entreprise->rccm }}</td>
                                <td>{{ $entreprise->adresse }}</td>
                                <td>
                                    <span class="bx b-soumis">{{ $entreprise->type_entreprise }}</span>
                                </td>

                                <td>{{ $entreprise->secteur_activite }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
</x-agent-layout>