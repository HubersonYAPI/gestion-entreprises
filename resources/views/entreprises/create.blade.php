<x-app-layout>
@include('components.ui-styles')
<div class="upg">

    {{-- Header --}}
    <div class="upg-hd">
        <div>
            <div class="upg-title">Nouvelle entreprise</div>
            <div class="upg-sub">Renseignez les informations de votre entreprise.</div>
        </div>
        <a href="{{ route('entreprises.index') }}" class="ubtn ubtn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Retour
        </a>
    </div>

    <div class="ucard">
        <div class="ucard-header">
            <div class="ucard-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
                Informations de l'entreprise
            </div>
        </div>
        <div class="ucard-body">
            <form method="POST" action="{{ route('entreprises.store') }}">
                @csrf
                <div class="ugrid ugrid-2" style="gap:1rem;margin-bottom:1rem">
                    <div class="ufield">
                        <label>Nom de l'entreprise *</label>
                        <input type="text" name="nom" value="{{ old('nom') }}" placeholder="Ex : Société ABC" required>
                        @error('nom')<span class="ufield-hint" style="color:var(--err)">{{ $message }}</span>@enderror
                    </div>
                    <div class="ufield">
                        <label>RCCM *</label>
                        <input type="text" name="rccm" value="{{ old('rccm') }}" placeholder="Ex : CI-ABJ-2024-B-1234" required>
                        @error('rccm')<span class="ufield-hint" style="color:var(--err)">{{ $message }}</span>@enderror
                    </div>
                    <div class="ufield">
                        <label>Adresse *</label>
                        <input type="text" name="adresse" value="{{ old('adresse') }}" placeholder="Ex : Plateau, Abidjan" required>
                        @error('adresse')<span class="ufield-hint" style="color:var(--err)">{{ $message }}</span>@enderror
                    </div>
                    <div class="ufield">
                        <label>Type d'entreprise *</label>
                        <input type="text" name="type_entreprise" value="{{ old('type_entreprise') }}" placeholder="Ex : SARL, SA, EI..." required>
                        @error('type_entreprise')<span class="ufield-hint" style="color:var(--err)">{{ $message }}</span>@enderror
                    </div>
                    <div class="ufield" style="grid-column:1/-1">
                        <label>Secteur d'activité *</label>
                        <input type="text" name="secteur_activite" value="{{ old('secteur_activite') }}" placeholder="Ex : Commerce, Industrie, Services..." required>
                        @error('secteur_activite')<span class="ufield-hint" style="color:var(--err)">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div style="height:1px;background:var(--border);margin:1.25rem 0"></div>
                <div class="uactions">
                    <a href="{{ route('entreprises.index') }}" class="ubtn ubtn-secondary">Annuler</a>
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