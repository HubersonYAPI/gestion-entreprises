<x-app-layout>
    <div class="max-w-3xl mx-auto mt-10 bg-white p-6 rounded shadow">

        <h2 class="text-xl font-bold mb-4">Modifier entreprise</h2>

        <form method="POST" action="{{ route('entreprises.update', $entreprise) }}">
            @csrf
            @method('PUT')

            <input type="text" name="nom" value="{{ $entreprise->nom }}" class="w-full border p-2 mb-3" required>
            <input type="text" name="rccm" value="{{ $entreprise->rccm }}" class="w-full border p-2 mb-3" required>
            <input type="text" name="adresse" value="{{ $entreprise->adresse }}" class="w-full border p-2 mb-3" required>
            <input type="text" name="type_entreprise" value="{{ $entreprise->type_entreprise }}" class="w-full border p-2 mb-3" required>
            <input type="text" name="secteur_activite" value="{{ $entreprise->secteur_activite }}" class="w-full border p-2 mb-3" required>

            <button class="bg-blue-600 text-white px-4 py-2 rounded">Mettre à jour</button>
        </form>

    </div>
</x-app-layout>