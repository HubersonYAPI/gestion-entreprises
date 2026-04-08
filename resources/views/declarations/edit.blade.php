<x-app-layout>
@include('components.ui-styles')
<div class="upg">

    {{-- Header --}}
    <div class="upg-hd">
        <div>
            <div class="upg-title">Modifier la déclaration</div>
            <div class="upg-sub">{{ $declaration->reference }}</div>
        </div>
        <a href="{{ route('declarations.index') }}" class="ubtn ubtn-secondary">
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
            <form method="POST" action="{{ route('declarations.update', $declaration) }}">
                @csrf @method('PUT')

                <div class="ugrid" style="gap:1rem;margin-bottom:1rem">
                    {{-- Entreprise --}}
                    <div class="ufield">
                        <label>Entreprise *</label>
                        <select name="entreprise_id" required>
                            @foreach($entreprises as $entreprise)
                                <option value="{{ $entreprise->id }}" {{ $entreprise->id == $declaration->entreprise_id ? 'selected' : '' }}>
                                    {{ $entreprise->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="ugrid ugrid-2" style="gap:1rem">
                        <div class="ufield">
                            <label>Nature de l'activité *</label>
                            <input type="text" name="nature_activite" value="{{ old('nature_activite', $declaration->nature_activite) }}" required>
                        </div>
                        <div class="ufield">
                            <label>Secteur d'activité *</label>
                            <input type="text" name="secteur_activite" value="{{ old('secteur_activite', $declaration->secteur_activite) }}" required>
                        </div>
                        <div class="ufield">
                            <label>Produits / Services *</label>
                            <input type="text" name="produits" value="{{ old('produits', $declaration->produits) }}" required>
                        </div>
                        <div class="ufield">
                            <label>Effectif *</label>
                            <input type="text" name="effectifs" value="{{ old('effectifs', $declaration->effectifs) }}" required>
                        </div>
                    </div>
                </div>

                <div style="height:1px;background:var(--border);margin:1.25rem 0"></div>
                <div class="uactions">
                    <a href="{{ route('declarations.index') }}" class="ubtn ubtn-secondary">Annuler</a>
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