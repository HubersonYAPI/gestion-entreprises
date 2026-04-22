<x-agent-layout>
<x-slot name="pageTitle">Rapports</x-slot>

<div class="pg">

    <div class="pg-hd">
        <div>
            <div class="pg-title">Rapports</div>
            <div class="pg-ref">Exportez et analysez les déclarations selon vos critères</div>
        </div>
        <a href="{{ request()->fullUrlWithQuery(['export' => 1]) }}"
           style="display:inline-flex;align-items:center;gap:.4rem;font-size:.79rem;font-weight:700;padding:.46rem .9rem;border-radius:8px;border:1px solid #a7f3d0;background:#ecfdf5;color:#059669;text-decoration:none;">
            <svg style="width:13px;height:13px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/>
                <line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
            Exporter CSV
        </a>
    </div>

    {{-- Filtres --}}
    <div class="card">
        <div class="ch">
            <div class="ct">Filtres</div>
            @if(request()->hasAny(['statut','secteur','date_debut','date_fin','search']))
                <a href="{{ route('agent.analyses.rapports') }}" style="font-size:.73rem;color:#dc2626;font-weight:600;text-decoration:none;">✕ Réinitialiser</a>
            @endif
        </div>
        <form method="GET" action="{{ route('agent.analyses.rapports') }}"
              style="padding:.9rem 1.1rem;display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:.75rem;">

            <div>
                <label class="field-l">Recherche</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Référence, entreprise…"
                    style="width:100%;padding:.45rem .7rem;border:1px solid var(--border);border-radius:7px;font-size:.8rem;color:var(--t1);background:#f8f9fd;outline:none;margin-top:.3rem;">
            </div>

            <div>
                <label class="field-l">Statut</label>
                <select name="statut" style="width:100%;padding:.45rem .7rem;border:1px solid var(--border);border-radius:7px;font-size:.8rem;color:var(--t1);background:#f8f9fd;outline:none;margin-top:.3rem;">
                    <option value="">Tous</option>
                    @foreach($statuts as $s)
                        <option value="{{ $s }}" {{ request('statut') === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="field-l">Secteur</label>
                <select name="secteur" style="width:100%;padding:.45rem .7rem;border:1px solid var(--border);border-radius:7px;font-size:.8rem;color:var(--t1);background:#f8f9fd;outline:none;margin-top:.3rem;">
                    <option value="">Tous</option>
                    @foreach($secteurs as $sec)
                        <option value="{{ $sec }}" {{ request('secteur') === $sec ? 'selected' : '' }}>{{ $sec }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="field-l">Date début</label>
                <input type="date" name="date_debut" value="{{ request('date_debut') }}"
                    style="width:100%;padding:.45rem .7rem;border:1px solid var(--border);border-radius:7px;font-size:.8rem;color:var(--t1);background:#f8f9fd;outline:none;margin-top:.3rem;">
            </div>

            <div>
                <label class="field-l">Date fin</label>
                <input type="date" name="date_fin" value="{{ request('date_fin') }}"
                    style="width:100%;padding:.45rem .7rem;border:1px solid var(--border);border-radius:7px;font-size:.8rem;color:var(--t1);background:#f8f9fd;outline:none;margin-top:.3rem;">
            </div>

            <div style="display:flex;align-items:flex-end;">
                <button type="submit" style="width:100%;padding:.46rem .9rem;border-radius:8px;border:none;background:var(--accent);color:#fff;font-size:.79rem;font-weight:700;cursor:pointer;">
                    Filtrer
                </button>
            </div>
        </form>
    </div>

    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem;padding:.1rem 0;">
        <span style="font-size:.8rem;color:var(--t2);font-weight:600;">{{ $declarations->total() }} résultat(s)</span>
        @if($totalMontant > 0)
            <span style="font-size:.8rem;color:#059669;font-weight:700;">Total : {{ number_format($totalMontant, 0, ',', ' ') }} FCFA</span>
        @endif
    </div>

    <div class="card">
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
                        <th>Montant</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($declarations as $decl)
                    <tr>
                        <td><span class="ref">{{ $decl->reference }}</span></td>
                        <td><div class="nm">{{ $decl->entreprise->nom ?? '—' }}</div></td>
                        <td><span class="mt">{{ Str::limit($decl->nature_activite ?? '—', 28) }}</span></td>
                        <td><span class="mt">{{ $decl->secteur_activite ?? '—' }}</span></td>
                        <td>
                            @php
                                $s = strtolower($decl->statut ?? '');
                                $cls = match(true) {
                                    str_contains($s,'soum')   => 'b-soumis',
                                    str_contains($s,'trait')  => 'b-trait',
                                    str_contains($s,'valid')  => 'b-valid',
                                    str_contains($s,'rejet')  => 'b-rej',
                                    str_contains($s,'pay')    => 'b-np',
                                    default                   => 'b-def',
                                };
                            @endphp
                            <span class="bx {{ $cls }}">{{ $decl->statut }}</span>
                        </td>
                        <td><span class="ph">{{ $decl->phase ?? '—' }}</span></td>
                        <td style="font-size:.78rem;font-weight:600;color:#059669;">
                            {{ $decl->paiement ? number_format($decl->paiement->montant, 0, ',', ' ').' F' : '—' }}
                        </td>
                        <td style="font-size:.76rem;color:var(--t3);">{{ $decl->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="acts">
                                <a href="{{ route('agent.declarations.show', $decl) }}" class="bi bi-eye" title="Voir">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9">
                            <div class="empty">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                Aucune déclaration ne correspond aux filtres.
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($declarations->hasPages())
        <div class="pager">{{ $declarations->links() }}</div>
        @endif
    </div>

</div>
</x-agent-layout>
