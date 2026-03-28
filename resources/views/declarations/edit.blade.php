<x-app-layout>
<div class="max-w-3xl mx-auto mt-10 bg-white p-6 rounded shadow">

    <h2 class="text-xl font-bold mb-4">Modifier déclaration</h2>

    <form method="POST" action="{{ route('declarations.update', $declaration) }}">
        @csrf
        @method('PUT')

        <select name="entreprise_id" class="w-full border p-2 mb-3">
            @foreach($entreprises as $entreprise)
                <option value="{{ $entreprise->id }}" @if($entreprise->id == $declaration->entreprise_id) selected @endif>
                    {{ $entreprise->nom }}
                </option>
            @endforeach
        </select>

        <!-- Nature de l'activite -->
        <div class="mb-4">
            <label class="block font-semibold">Nature de l'activite</label>
            <input type="text" name="nature_activite" class="w-full border p-2 rounded"
                    value="{{ $declaration->nature_activite }}" required>
        </div>

        <!-- Secteur de l'activite -->
        <div class="mb-4">
            <label class="block font-semibold">Secteur de l'activite</label>
            <input type="text" name="secteur_activite" class="w-full border p-2 rounded"
                    value="{{ $declaration->secteur_activite }}" required>
        </div>

        <!-- Produits -->
        <div class="mb-4">
            <label class="block font-semibold">Produits</label>
            <input type="text" name="produits" class="w-full border p-2 rounded"
                    value="{{ $declaration->produits }}" required>
        </div>

        <!-- Effectifs -->
        <div class="mb-4">
            <label class="block font-semibold">Effectif</label>
            <input type="text" name="effectifs" class="w-full border p-2 rounded"
                    value="{{ $declaration->effectifs }}" required>
        </div>

        <div class="mt-6 flex gap-3">
            <!-- 🔙 Bouton retour -->
            <a href="{{ route('declarations.index') }}" 
            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                Retour
            </a>

            <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                Mettre à jour
            </button>
        </div>
        
    </form>

</div>
</x-app-layout>