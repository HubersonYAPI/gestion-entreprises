<x-app-layout>
    <div class="max-w-3xl mx-auto mt-10 bg-white p-6 rounded shadow">

        <h2 class="text-xl font-bold mb-4">Créer une entreprise</h2>

        <form method="POST" action="{{ route('entreprises.store') }}">
            @csrf

            <input type="text" name="nom" placeholder="Nom" class="w-full border p-2 mb-3" required>
            <input type="text" name="rccm" placeholder="RCCM" class="w-full border p-2 mb-3" required>
            <input type="text" name="adresse" placeholder="Adresse" class="w-full border p-2 mb-3" required>
            <input type="text" name="type_entreprise" placeholder="Type" class="w-full border p-2 mb-3" required>
            <input type="text" name="secteur_activite" placeholder="Secteur" class="w-full border p-2 mb-3" required>

            <button class="bg-blue-600 text-white px-4 py-2 rounded">Enregistrer</button>
        </form>

    </div>
</x-app-layout>