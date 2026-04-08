<x-app-layout>
@include('components.ui-styles')
<div class="upg">

    {{-- Header --}}
    <div class="upg-hd">
        <div>
            <div class="upg-title">Modifier l'entreprise</div>
            <div class="upg-sub">{{ $entreprise->nom }}</div>
        </div>
        <a href="{{ route('entreprises.index') }}" class="ubtn ubtn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Retour
        </a>
    </div>

    <div class="ucard">
        <div class="ucard-header">
            <div class="ucard-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Modifier les informations
            </div>
        </div>
        <div class="ucard-body">
            <form method="POST" action="{{ route('entreprises.update', $entreprise) }}">
                @csrf @method('PUT')
                <div class="ugrid ugrid-2" style="gap:1rem;margin-bottom:1rem">
                    <div class="ufield">
                        <label>Nom de l'entreprise *</label>
                        <input type="text" name="nom" value="{{ old('nom', $entreprise->nom) }}" required>
                        @error('nom')<span class="ufield-hint" style="color:var(--err)">{{ $message }}</span>@enderror
                    </div>
                    <div class="ufield">
                        <label>RCCM *</label>
                        <input type="text" name="rccm" value="{{ old('rccm', $entreprise->rccm) }}" required>
                        @error('rccm')<span class="ufield-hint" style="color:var(--err)">{{ $message }}</span>@enderror
                    </div>
                    <div class="ufield">
                        <label>Adresse *</label>
                        <input type="text" name="adresse" value="{{ old('adresse', $entreprise->adresse) }}" required>
                        @error('adresse')<span class="ufield-hint" style="color:var(--err)">{{ $message }}</span>@enderror
                    </div>
                    <div class="ufield">
                        <label>Type d'entreprise *</label>
                        <input type="text" name="type_entreprise" value="{{ old('type_entreprise', $entreprise->type_entreprise) }}" required>
                        @error('type_entreprise')<span class="ufield-hint" style="color:var(--err)">{{ $message }}</span>@enderror
                    </div>
                    <div class="ufield" style="grid-column:1/-1">
                        <label>Secteur d'activité *</label>
                        <input type="text" name="secteur_activite" value="{{ old('secteur_activite', $entreprise->secteur_activite) }}" required>
                        @error('secteur_activite')<span class="ufield-hint" style="color:var(--err)">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div style="height:1px;background:var(--border);margin:1.25rem 0"></div>
                <div class="uactions">
                    <a href="{{ route('entreprises.index') }}" class="ubtn ubtn-secondary">Annuler</a>
                    <button type="submit" class="ubtn ubtn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
</x-app-layout>