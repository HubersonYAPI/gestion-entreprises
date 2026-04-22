<x-app-layout>
@include('components.ui-styles')

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

<div class="db" style="width:100%;overflow-x:hidden;">

    {{-- ── Header ── --}}
    <div class="db-hd">
        <div>
            <div class="db-title">Bonjour, {{ Auth::user()->name }} 👋</div>
            <div class="db-sub">Voici un aperçu de votre activité déclarative.</div>
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

    {{-- ── KPI Cards ── --}}
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
        {{-- Total --}}
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-blue">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
                </div>
                <span class="sc-badge neu">Total</span>
            </div>
            <div class="sc-val">{{ $total }}</div>
            <div class="sc-lbl">Déclarations</div>
        </div>
        {{-- Entreprises --}}
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico" style="background:#f0fdf4;color:#16a34a;width:36px;height:36px;border-radius:9px;display:flex;align-items:center;justify-content:center;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><path d="M9 22V12h6v10"/></svg>
                </div>
                <span class="sc-badge neu">Portefeuille</span>
            </div>
            <div class="sc-val">{{ $totalEntreprises }}</div>
            <div class="sc-lbl">Entreprises</div>
        </div>
        {{-- Validées --}}
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-purple">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg>
                </div>
                <span class="sc-badge" style="background:#ede9fe;color:#5b21b6;">{{ $tauxValid }}%</span>
            </div>
            <div class="sc-val">{{ $valide }}</div>
            <div class="sc-lbl">Validées</div>
        </div>
        {{-- Rejetées --}}
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-red">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                </div>
                <span class="sc-badge dn">✗</span>
            </div>
            <div class="sc-val">{{ $rejete }}</div>
            <div class="sc-lbl">Rejetées</div>
        </div>
        {{-- Soumises --}}
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-blue">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <span class="sc-badge neu">En attente</span>
            </div>
            <div class="sc-val">{{ $soumis }}</div>
            <div class="sc-lbl">Soumises</div>
        </div>
        {{-- En traitement --}}
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-amber">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                </div>
                <span class="sc-badge neu">Cours</span>
            </div>
            <div class="sc-val">{{ $enTrait }}</div>
            <div class="sc-lbl">En traitement</div>
        </div>
        {{-- Soldées --}}
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-orange">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                </div>
                <span class="sc-badge neu">Soldées</span>
            </div>
            <div class="sc-val">{{ $paye }}</div>
            <div class="sc-lbl">Soldées</div>
        </div>
        {{-- Brouillons --}}
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-gray">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </div>
                <span class="sc-badge neu">Brouillon</span>
            </div>
            <div class="sc-val">{{ $brou }}</div>
            <div class="sc-lbl">Brouillons</div>
        </div>
    </div>

    {{-- ── Graphiques ligne 1 ── --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
        {{-- Évolution mensuelle --}}
        <div class="ucard">
            <div class="ucard-header">
                <div class="ucard-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    Déclarations par mois
                </div>
                <span style="font-size:.7rem;font-weight:700;background:#f1f5f9;color:#64748b;padding:2px 8px;border-radius:20px;">12 mois</span>
            </div>
            <div style="padding:1rem 1.2rem;height:240px;">
                <canvas id="chartMois"></canvas>
            </div>
        </div>
        {{-- Donut statuts --}}
        <div class="ucard">
            <div class="ucard-header">
                <div class="ucard-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a10 10 0 010 20"/></svg>
                    Répartition par statut
                </div>
            </div>
            <div style="padding:1rem 1.2rem;height:240px;display:flex;align-items:center;justify-content:center;">
                <canvas id="chartStatut" style="max-height:220px;"></canvas>
            </div>
        </div>
    </div>

    {{-- ── Graphiques ligne 2 ── --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
        {{-- Secteurs --}}
        <div class="ucard">
            <div class="ucard-header">
                <div class="ucard-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    Secteurs d'activité
                </div>
                <span style="font-size:.7rem;font-weight:700;background:#f1f5f9;color:#64748b;padding:2px 8px;border-radius:20px;">{{ count($secteurLabels) }} secteur(s)</span>
            </div>
            <div style="padding:1rem 1.2rem;height:240px;">
                <canvas id="chartSecteur"></canvas>
            </div>
        </div>
        {{-- Barres de progression --}}
        <div class="ucard">
            <div class="ucard-header">
                <div class="ucard-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                    Taux de traitement
                </div>
            </div>
            <div style="padding:1.2rem 1.4rem;display:flex;flex-direction:column;gap:1.1rem;justify-content:center;height:240px;">
                @php
                    $tauxRejet  = $total > 0 ? round(($rejete  / $total) * 100, 1) : 0;
                    $tauxSoumis = $total > 0 ? round(($soumis  / $total) * 100, 1) : 0;
                    $tauxTrait  = $total > 0 ? round(($enTrait / $total) * 100, 1) : 0;
                    $tauxApprv  = $total > 0 ? round(($approuve/ $total) * 100, 1) : 0;
                @endphp

                @foreach([
                    ['Validées',      $tauxValid, 'linear-gradient(90deg,#7c3aed,#a78bfa)', '#5b21b6'],
                    ['Approuvées',    $tauxApprv, 'linear-gradient(90deg,#059669,#10b981)', '#065f46'],
                    ['En traitement', $tauxTrait, 'linear-gradient(90deg,#d97706,#f59e0b)', '#92400e'],
                    ['Rejetées',      $tauxRejet, 'linear-gradient(90deg,#dc2626,#ef4444)', '#991b1b'],
                    ['En attente',    $tauxSoumis,'linear-gradient(90deg,#2563eb,#60a5fa)', '#1d4ed8'],
                ] as [$label, $taux, $gradient, $color])
                <div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:.35rem;">
                        <span style="font-size:.75rem;font-weight:600;color:var(--t2);">{{ $label }}</span>
                        <span style="font-size:.75rem;font-weight:700;color:{{ $color }};">{{ $taux }}%</span>
                    </div>
                    <div style="height:7px;background:#f1f5f9;border-radius:99px;overflow:hidden;">
                        <div style="height:100%;width:{{ $taux }}%;background:{{ $gradient }};border-radius:99px;transition:width .6s ease;"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ── Tableau déclarations récentes ── --}}
    <div class="ucard">
        <div class="ucard-header">
            <div class="ucard-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                    <path d="M14 2v6h6"/>
                </svg>
                Déclarations récentes
            </div>
            <a href="{{ route('declarations.index') }}"
               style="font-size:.74rem;font-weight:600;color:var(--accent);text-decoration:none;display:inline-flex;align-items:center;gap:.3rem;">
                Voir tout
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:12px;height:12px;"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>

        {{-- Filtres --}}
        <div style="padding:.65rem 1.25rem;border-bottom:1px solid var(--border);display:flex;gap:.45rem;flex-wrap:wrap;">
            <a href="{{ route('dashboard') }}"
               class="flt {{ !request('statut') ? 'on' : '' }}">Toutes</a>
            @foreach([
                'brouillon'     => 'Brouillons',
                'soumis'        => 'Soumises',
                'approuve'      => 'Approuvées',
                'en_traitement' => 'En traitement',
                'valide'        => 'Validées',
                'rejete'        => 'Rejetées',
                'paye'          => 'Soldées',
            ] as $slug => $label)
            <a href="{{ route('dashboard', ['statut' => $slug]) }}"
               class="flt {{ request('statut') === $slug ? 'on' : '' }}">{{ $label }}</a>
            @endforeach
        </div>

        <div class="utbl-wrap">
            <table class="utbl">
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Entreprise</th>
                        <th>Statut</th>
                        <th>Phase</th>
                        <th>Mise à jour</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($declarations as $declaration)
                    @php
                        $sMap = [
                            'brouillon'     => ['Brouillon',     'ub-indigo'],
                            'soumis'        => ['Soumise',       'ub-blue'],
                            'approuve'      => ['Approuvée',     'ub-green'],
                            'paye'          => ['Soldée',        'ub-teal'],
                            'en_traitement' => ['En traitement', 'ub-yellow'],
                            'valide'        => ['Validée',       'ub-purple'],
                            'rejete'        => ['Rejetée',       'ub-red'],
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

                        {{-- Actions dropdown --}}
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

                                    <a href="{{ route('declarations.show', $declaration) }}" class="act-item c-view">
                                        <span class="act-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></span>
                                        Voir le détail
                                    </a>

                                    @if($declaration->statut === 'brouillon')
                                        <a href="{{ route('documents.index', $declaration) }}" class="act-item c-doc">
                                            <span class="act-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8"/></svg></span>
                                            Documents
                                        </a>
                                        <a href="{{ route('declarations.edit', $declaration) }}" class="act-item c-edit">
                                            <span class="act-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></span>
                                            Modifier
                                        </a>
                                        <div class="act-sep"></div>
                                        <form method="POST" action="{{ route('declarations.submit', $declaration) }}" style="display:contents">
                                            @csrf
                                            <button type="submit" class="act-item c-submit"
                                                    onclick="return confirm('Soumettre cette déclaration ?')">
                                                <span class="act-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg></span>
                                                Soumettre
                                            </button>
                                        </form>
                                        <div class="act-sep"></div>
                                        <form method="POST" action="{{ route('declarations.destroy', $declaration) }}"
                                              style="display:contents"
                                              onsubmit="return confirm('Supprimer cette déclaration ?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="act-item c-del">
                                                <span class="act-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg></span>
                                                Supprimer
                                            </button>
                                        </form>

                                    @elseif($declaration->statut === 'rejete')
                                        <a href="{{ route('documents.index', $declaration) }}" class="act-item c-doc">
                                            <span class="act-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8"/></svg></span>
                                            Voir les documents
                                        </a>

                                    @elseif($declaration->statut === 'valide')
                                        <a href="{{ route('paiement.show', $declaration) }}" class="act-item c-pay">
                                            <span class="act-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/><circle cx="7" cy="15" r="1"/><circle cx="11" cy="15" r="1"/></svg></span>
                                            Procéder au paiement
                                        </a>

                                    @elseif($declaration->statut === 'terminé' && !empty($declaration->attestation?->file_path))
                                        <a href="{{ asset('storage/'.$declaration->attestation->file_path) }}"
                                           target="_blank" class="act-item c-dl">
                                            <span class="act-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v12"/><polyline points="7 10 12 15 17 10"/><path d="M5 21h14"/></svg></span>
                                            Télécharger attestation
                                        </a>
                                    @endif

                                </div>
                            </div>
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

        {{-- Pagination --}}
        @if($declarations->hasPages())
        <div style="padding:.7rem 1.2rem;border-top:1px solid var(--border);">
            {{ $declarations->links() }}
        </div>
        @endif
    </div>

</div>

{{-- ── Chart.js ── --}}
@push('scripts')
<script>
const moisLabels    = @json($moisLabels);
const moisValues    = @json($moisValues);
const statutLabels  = @json($statutLabels);
const statutValues  = @json($statutValues);
const secteurLabels = @json($secteurLabels);
const secteurValues = @json($secteurValues);

const ACCENT  = '#2563eb';
const PALETTE = ['#2563eb','#7c3aed','#059669','#dc2626','#d97706','#0f766e','#db2777','#9333ea'];

const baseOpts = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false } }
};

// ── Déclarations par mois ──
new Chart(document.getElementById('chartMois'), {
    type: 'line',
    data: {
        labels: moisLabels,
        datasets: [{
            data: moisValues,
            borderColor: ACCENT,
            backgroundColor: 'rgba(37,99,235,0.08)',
            borderWidth: 2.5,
            pointBackgroundColor: ACCENT,
            pointRadius: 4,
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        ...baseOpts,
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 10 } } },
            y: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 } }, beginAtZero: true }
        }
    }
});

// ── Donut statuts ──
new Chart(document.getElementById('chartStatut'), {
    type: 'doughnut',
    data: {
        labels: statutLabels,
        datasets: [{
            data: statutValues,
            backgroundColor: PALETTE,
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'right',
                labels: { font: { size: 11 }, boxWidth: 12, padding: 14 }
            }
        },
        cutout: '62%'
    }
});

// ── Secteurs (barres horizontales) ──
new Chart(document.getElementById('chartSecteur'), {
    type: 'bar',
    data: {
        labels: secteurLabels,
        datasets: [{
            data: secteurValues,
            backgroundColor: secteurValues.map((_, i) => PALETTE[i % PALETTE.length] + 'cc'),
            borderRadius: 5
        }]
    },
    options: {
        ...baseOpts,
        indexAxis: 'y',
        scales: {
            x: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 } }, beginAtZero: true },
            y: { grid: { display: false }, ticks: { font: { size: 10 } } }
        }
    }
});
</script>
@endpush

{{-- ── Dropdown toggle ── --}}
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