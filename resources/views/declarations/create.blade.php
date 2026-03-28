<x-app-layout>
<div class="max-w-3xl mx-auto mt-10 bg-white p-6 rounded shadow">

    <h2 class="text-xl font-bold mb-4">Nouvelle déclaration</h2>

    <form method="POST" action="{{ route('declarations.store') }}">
        @csrf

        <select name="entreprise_id" class="w-full border p-2 mb-3">
            @foreach($entreprises as $entreprise)
                <option value="{{ $entreprise->id }}">{{ $entreprise->nom }}</option>
            @endforeach
        </select>

        <input type="text" name="nature_activite" placeholder="Nature de l'activité" class="w-full border p-2 mb-3" required>
        <input type="text" name="secteur_activite" placeholder="Secteur de l'activité" class="w-full border p-2 mb-3" required>
        <input type="text" name="produits" placeholder="Produits" class="w-full border p-2 mb-3" required>
        <input type="text" name="effectifs" placeholder="Effectif" class="w-full border p-2 mb-3" required>


        <button class="bg-blue-600 text-white px-4 py-2 rounded">
            Créer
        </button>
    </form>

</div>
</x-app-layout>