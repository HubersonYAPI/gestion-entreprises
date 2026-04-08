<x-app-layout>
@include('components.ui-styles')
<div class="upg">

    {{-- Header --}}
    <div class="upg-hd">
        <div>
            <div class="upg-title">Nouvelle déclaration</div>
            <div class="upg-sub">Renseignez les informations relatives à votre activité.</div>
        </div>
        <a href="{{ route('declarations.index') }}" class="ubtn ubtn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Retour
        </a>
    </div>

    <div class="ucard">
        <div class="ucard-header">
            <div class="ucard-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
                Informations de l'activité
            </div>
        </div>
        <div class="ucard-body">
            <form method="POST" action="{{ route('declarations.store') }}">
                @csrf

                <div class="ugrid" style="gap:1rem;margin-bottom:1rem">

                    {{-- Entreprise --}}
                    <div class="ufield">
                        <label>Entreprise concernée *</label>
                        <select name="entreprise_id" required>
                            <option value="" disabled selected>Sélectionner une entreprise</option>
                            @foreach($entreprises as $entreprise)
                                <option value="{{ $entreprise->id }}" {{ old('entreprise_id') == $entreprise->id ? 'selected' : '' }}>
                                    {{ $entreprise->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('entreprise_id')<span class="ufield-hint" style="color:var(--err)">{{ $message }}</span>@enderror
                    </div>

                    <div class="ugrid ugrid-2" style="gap:1rem">
                        <div class="ufield">
                            <label>Nature de l'activité *</label>
                            <input type="text" name="nature_activite" value="{{ old('nature_activite') }}" placeholder="Ex : Négoce de matériel informatique" required>
                            @error('nature_activite')<span class="ufield-hint" style="color:var(--err)">{{ $message }}</span>@enderror
                        </div>
                        <div class="ufield">
                            <label>Secteur d'activité *</label>
                            <input type="text" name="secteur_activite" value="{{ old('secteur_activite') }}" placeholder="Ex : Technologie, Commerce..." required>
                            @error('secteur_activite')<span class="ufield-hint" style="color:var(--err)">{{ $message }}</span>@enderror
                        </div>
                        <div class="ufield">
                            <label>Produits / Services *</label>
                            <input type="text" name="produits" value="{{ old('produits') }}" placeholder="Ex : Ordinateurs, smartphones..." required>
                            @error('produits')<span class="ufield-hint" style="color:var(--err)">{{ $message }}</span>@enderror
                        </div>
                        <div class="ufield">
                            <label>Effectif (nombre d'employés) *</label>
                            <input type="text" name="effectifs" value="{{ old('effectifs') }}" placeholder="Ex : 12" required>
                            @error('effectifs')<span class="ufield-hint" style="color:var(--err)">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <div style="background:var(--acc-bg);border:1px solid #bfdbfe;border-radius:9px;padding:.75rem 1rem;font-size:.78rem;color:var(--acc-txt);margin-bottom:1.25rem">
                    <strong>ℹ️ Information :</strong> La déclaration sera créée en mode <em>brouillon</em>. Vous pourrez y ajouter vos documents avant de la soumettre.
                </div>

                <div style="height:1px;background:var(--border);margin:1.25rem 0"></div>
                <div class="uactions">
                    <a href="{{ route('declarations.index') }}" class="ubtn ubtn-secondary">Annuler</a>
                    <button type="submit" class="ubtn ubtn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        Créer la déclaration
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
</x-app-layout>