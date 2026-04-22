<x-agent-layout>
<x-slot name="pageTitle">Dashboard</x-slot>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

<div class="pg">

    {{-- En-tête --}}
    <div class="pg-hd">
        <div>
            <div class="pg-title">Dashboard</div>
            <div class="pg-ref">Tableau de bord analytique — données en temps réel</div>
        </div>
        <div style="font-size:.75rem;color:var(--t3);background:var(--white);border:1px solid var(--border);padding:.35rem .8rem;border-radius:8px;">
            {{ now()->translatedFormat('d F Y') }}
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="stats">
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-blue">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
                </div>
                <span class="sc-badge neu">Total</span>
            </div>
            <div class="sc-val">{{ $totalDeclarations }}</div>
            <div class="sc-lbl">Déclarations</div>
        </div>
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-green">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
                <span class="sc-badge up">+{{ $tauxValid }}%</span>
            </div>
            <div class="sc-val">{{ $validees }}</div>
            <div class="sc-lbl">Validées</div>
        </div>
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-red">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                </div>
                <span class="sc-badge dn">Rejetées</span>
            </div>
            <div class="sc-val">{{ $rejetees }}</div>
            <div class="sc-lbl">Rejetées</div>
        </div>
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-amber">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <span class="sc-badge neu">En attente</span>
            </div>
            <div class="sc-val">{{ $soumises }}</div>
            <div class="sc-lbl">Soumises</div>
        </div>
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-violet">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><path d="M9 22V12h6v10"/></svg>
                </div>
                <span class="sc-badge neu">Total</span>
            </div>
            <div class="sc-val">{{ $totalEntreprises }}</div>
            <div class="sc-lbl">Entreprises</div>
        </div>
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-teal">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <span class="sc-badge neu">Total</span>
            </div>
            <div class="sc-val">{{ $totalGerants }}</div>
            <div class="sc-lbl">Gérants</div>
        </div>
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-green">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                </div>
                <span class="sc-badge up">Recettes</span>
            </div>
            <div class="sc-val" style="font-size:1.1rem;">{{ number_format($totalPaiements, 0, ',', ' ') }}</div>
            <div class="sc-lbl">Montant total (FCFA)</div>
        </div>
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-blue">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                </div>
                <span class="sc-badge up">Taux</span>
            </div>
            <div class="sc-val">{{ $tauxValid }}%</div>
            <div class="sc-lbl">Taux de validation</div>
        </div>
    </div>

    {{-- Graphiques ligne 1 --}}
    <div class="two">
        <div class="card">
            <div class="ch">
                <div class="ct">Déclarations par mois</div>
                <span class="cc">12 mois</span>
            </div>
            <div style="padding:1rem 1.2rem;height:260px;">
                <canvas id="chartMois"></canvas>
            </div>
        </div>
        <div class="card">
            <div class="ch">
                <div class="ct">Répartition par statut</div>
            </div>
            <div style="padding:1rem 1.2rem;height:260px;display:flex;align-items:center;justify-content:center;">
                <canvas id="chartStatut" style="max-height:240px;"></canvas>
            </div>
        </div>
    </div>

    {{-- Graphiques ligne 2 --}}
    <div class="two">
        <div class="card">
            <div class="ch">
                <div class="ct">Top secteurs d'activité</div>
                <span class="cc">{{ count($secteurLabels) }} secteurs</span>
            </div>
            <div style="padding:1rem 1.2rem;height:260px;">
                <canvas id="chartSecteur"></canvas>
            </div>
        </div>
        <div class="card">
            <div class="ch">
                <div class="ct">Recettes mensuelles (FCFA)</div>
                <span class="cc">12 mois</span>
            </div>
            <div style="padding:1rem 1.2rem;height:260px;">
                <canvas id="chartPaiements"></canvas>
            </div>
        </div>
    </div>

    {{-- Barres de progression --}}
    <div class="card">
        <div class="ch">
            <div class="ct">Taux de traitement global</div>
        </div>
        <div style="padding:1.2rem 1.4rem;display:grid;gap:1rem;">
            @php
                $tauxRejet  = $totalDeclarations > 0 ? round(($rejetees / $totalDeclarations) * 100, 1) : 0;
                $tauxSoumis = $totalDeclarations > 0 ? round(($soumises / $totalDeclarations) * 100, 1) : 0;
            @endphp
            <div>
                <div style="display:flex;justify-content:space-between;margin-bottom:.4rem;">
                    <span style="font-size:.78rem;font-weight:600;color:var(--t2);">Validées</span>
                    <span style="font-size:.78rem;font-weight:700;color:#059669;">{{ $tauxValid }}%</span>
                </div>
                <div class="prog-bar"><div class="prog-fill" style="width:{{ $tauxValid }}%;background:linear-gradient(90deg,#059669,#10b981);"></div></div>
            </div>
            <div>
                <div style="display:flex;justify-content:space-between;margin-bottom:.4rem;">
                    <span style="font-size:.78rem;font-weight:600;color:var(--t2);">Rejetées</span>
                    <span style="font-size:.78rem;font-weight:700;color:#dc2626;">{{ $tauxRejet }}%</span>
                </div>
                <div class="prog-bar"><div class="prog-fill" style="width:{{ $tauxRejet }}%;background:linear-gradient(90deg,#dc2626,#ef4444);"></div></div>
            </div>
            <div>
                <div style="display:flex;justify-content:space-between;margin-bottom:.4rem;">
                    <span style="font-size:.78rem;font-weight:600;color:var(--t2);">En attente</span>
                    <span style="font-size:.78rem;font-weight:700;color:#d97706;">{{ $tauxSoumis }}%</span>
                </div>
                <div class="prog-bar"><div class="prog-fill" style="width:{{ $tauxSoumis }}%;background:linear-gradient(90deg,#d97706,#f59e0b);"></div></div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
const moisLabels     = @json($moisLabels);
const moisValues     = @json($moisValues);
const statutLabels   = @json($statutLabels);
const statutValues   = @json($statutValues);
const secteurLabels  = @json($secteurLabels);
const secteurValues  = @json($secteurValues);
const paiementLabels = @json($paiementMoisLabels);
const paiementValues = @json($paiementMoisValues);

const ACCENT  = '#2f54eb';
const PALETTE = ['#2f54eb','#059669','#dc2626','#d97706','#7c3aed','#0f766e','#db2777','#9333ea'];
const baseOpts = { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } };

new Chart(document.getElementById('chartMois'), {
    type: 'line',
    data: { labels: moisLabels, datasets: [{ data: moisValues, borderColor: ACCENT, backgroundColor: 'rgba(47,84,235,0.08)', borderWidth: 2.5, pointBackgroundColor: ACCENT, pointRadius: 4, tension: 0.4, fill: true }] },
    options: { ...baseOpts, scales: { x: { grid: { display: false }, ticks: { font: { size: 10 } } }, y: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 } }, beginAtZero: true } } }
});

new Chart(document.getElementById('chartStatut'), {
    type: 'doughnut',
    data: { labels: statutLabels, datasets: [{ data: statutValues, backgroundColor: PALETTE, borderWidth: 2, borderColor: '#fff' }] },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: true, position: 'right', labels: { font: { size: 11 }, boxWidth: 12, padding: 14 } } }, cutout: '62%' }
});

new Chart(document.getElementById('chartSecteur'), {
    type: 'bar',
    data: { labels: secteurLabels, datasets: [{ data: secteurValues, backgroundColor: secteurValues.map((_, i) => PALETTE[i % PALETTE.length] + 'cc'), borderRadius: 5 }] },
    options: { ...baseOpts, indexAxis: 'y', scales: { x: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 } }, beginAtZero: true }, y: { grid: { display: false }, ticks: { font: { size: 10 } } } } }
});

new Chart(document.getElementById('chartPaiements'), {
    type: 'bar',
    data: { labels: paiementLabels, datasets: [{ data: paiementValues, backgroundColor: 'rgba(5,150,105,0.75)', borderRadius: 5 }] },
    options: { ...baseOpts, scales: { x: { grid: { display: false }, ticks: { font: { size: 10 } } }, y: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 } }, beginAtZero: true } } }
});
</script>
@endpush
</x-agent-layout>
