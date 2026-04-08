<x-app-layout>
@include('components.ui-styles')
<div class="upg">

    {{-- Header --}}
    <div class="upg-hd">
        <div>
            <div class="upg-title">Profil du Gérant</div>
            <div class="upg-sub">Vos informations personnelles enregistrées sur la plateforme.</div>
        </div>
        <a href="{{ route('gerant.edit') }}" class="ubtn ubtn-warn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            Modifier le profil
        </a>
    </div>

    <div class="ucard">
        <div class="ucard-header">
            <div class="ucard-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Informations personnelles
            </div>
        </div>
        <div class="ucard-body">

            {{-- Avatar + nom --}}
            <div style="display:flex;align-items:center;gap:1rem;padding:.75rem 0 1.25rem;border-bottom:1px solid var(--border);margin-bottom:1.25rem">
                <div style="width:52px;height:52px;border-radius:50%;background:linear-gradient(135deg,#2563eb,#7c3aed);display:flex;align-items:center;justify-content:center;font-size:1.2rem;font-weight:800;color:#fff;flex-shrink:0">
                    {{ strtoupper(substr($gerant->nom ?? 'G', 0, 1)) }}
                </div>
                <div>
                    <div style="font-size:1rem;font-weight:700;color:var(--t1)">{{ $gerant->nom }} {{ $gerant->prenoms }}</div>
                    <div style="font-size:.78rem;color:var(--t3)">Gérant enregistré</div>
                </div>
            </div>

            <div class="uinfo-grid">
                <div class="uinfo-field">
                    <span class="uinfo-label">Nom</span>
                    <span class="uinfo-value">{{ $gerant->nom }}</span>
                </div>
                <div class="uinfo-field">
                    <span class="uinfo-label">Prénoms</span>
                    <span class="uinfo-value">{{ $gerant->prenoms }}</span>
                </div>
                <div class="uinfo-field">
                    <span class="uinfo-label">Contact</span>
                    <span class="uinfo-value">{{ $gerant->contact }}</span>
                </div>
            </div>

            @if($gerant->piece_identite)
            <div style="margin-top:1.25rem;padding-top:1.25rem;border-top:1px solid var(--border)">
                <div class="uinfo-label" style="margin-bottom:.5rem">Pièce d'identité</div>
                <a href="{{ asset('storage/'.$gerant->piece_identite) }}" target="_blank" class="ufile-link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
                    Voir la pièce d'identité
                </a>
            </div>
            @else
            <div style="margin-top:1.25rem;padding-top:1.25rem;border-top:1px solid var(--border)">
                <div style="display:flex;align-items:center;gap:.5rem;font-size:.78rem;color:var(--warn);background:var(--warn-bg);border:1px solid var(--warn-border);padding:.6rem .85rem;border-radius:8px">
                    <svg style="width:14px;height:14px;flex-shrink:0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    Aucune pièce d'identité téléversée.
                    <a href="{{ route('gerant.edit') }}" style="font-weight:700;text-decoration:underline;color:inherit">Compléter le profil</a>
                </div>
            </div>
            @endif
        </div>
    </div>

</div>
</x-app-layout>