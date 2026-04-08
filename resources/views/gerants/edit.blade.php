<x-app-layout>
@include('components.ui-styles')
<div class="upg">

    {{-- Header --}}
    <div class="upg-hd">
        <div>
            <div class="upg-title">Modifier le profil Gérant</div>
            <div class="upg-sub">Mettez à jour vos informations personnelles.</div>
        </div>
        <a href="{{ route('gerant.show') }}" class="ubtn ubtn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Retour
        </a>
    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div class="ua-ok">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="ucard">
        <div class="ucard-header">
            <div class="ucard-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Informations personnelles
            </div>
        </div>
        <div class="ucard-body">
            <form method="POST" action="{{ route('gerant.update') }}" enctype="multipart/form-data">
                @csrf
                <div class="ugrid ugrid-2" style="gap:1rem;margin-bottom:1.25rem">
                    <div class="ufield">
                        <label>Nom *</label>
                        <input type="text" name="nom" value="{{ old('nom', $gerant->nom ?? '') }}" placeholder="Ex : KOUAMÉ" required>
                        @error('nom')<span class="ufield-hint" style="color:var(--err)">{{ $message }}</span>@enderror
                    </div>
                    <div class="ufield">
                        <label>Prénoms *</label>
                        <input type="text" name="prenoms" value="{{ old('prenoms', $gerant->prenoms ?? '') }}" placeholder="Ex : Jean-Baptiste" required>
                        @error('prenoms')<span class="ufield-hint" style="color:var(--err)">{{ $message }}</span>@enderror
                    </div>
                    <div class="ufield" style="grid-column:1/-1">
                        <label>Contact (téléphone) *</label>
                        <input type="text" name="contact" value="{{ old('contact', $gerant->contact ?? '') }}" placeholder="Ex : +225 07 00 00 00 00" required>
                        @error('contact')<span class="ufield-hint" style="color:var(--err)">{{ $message }}</span>@enderror
                    </div>
                </div>

                {{-- Pièce d'identité --}}
                <div style="border-top:1px solid var(--border);padding-top:1.25rem">
                    <div style="font-size:.78rem;font-weight:700;color:var(--t2);margin-bottom:.75rem">Pièce d'identité</div>

                    @if($gerant && $gerant->piece_identite)
                    <div style="display:flex;align-items:center;gap:.75rem;padding:.65rem .9rem;background:var(--ok-bg);border:1px solid var(--ok-border);border-radius:8px;margin-bottom:.75rem">
                        <svg style="width:14px;height:14px;color:var(--ok);flex-shrink:0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        <span style="font-size:.78rem;color:var(--ok);font-weight:600">Document déjà téléversé.</span>
                        <a href="{{ asset('storage/'.$gerant->piece_identite) }}" target="_blank" class="ufile-link" style="margin-left:auto">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            Voir
                        </a>
                    </div>
                    @endif

                    <div class="ufield">
                        <label>{{ $gerant && $gerant->piece_identite ? 'Remplacer la pièce d\'identité' : 'Téléverser la pièce d\'identité *' }}</label>
                        <div class="ufile-zone">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:20px;height:20px;flex-shrink:0;opacity:.4"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                            <input type="file" name="piece_identite" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                        <span class="ufield-hint">PDF, JPG, PNG — max 5 Mo</span>
                        @error('piece_identite')<span class="ufield-hint" style="color:var(--err)">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div style="height:1px;background:var(--border);margin:1.25rem 0"></div>
                <div class="uactions">
                    <a href="{{ route('gerant.show') }}" class="ubtn ubtn-secondary">Annuler</a>
                    <button type="submit" class="ubtn ubtn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
</x-app-layout>