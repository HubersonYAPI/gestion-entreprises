<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Détail de la Déclaration</h2>
    </x-slot>

    <div class="p-6">

        <!-- Référence -->
        <div class="bg-white shadow rounded p-4 mb-4">
            <h3 class="font-bold text-lg mb-2">Référence</h3>
            <p>{{ $declaration->reference }}</p>
        </div>

        <!-- Infos Gérant -->
        <div class="bg-white shadow rounded p-4 mb-4">
            <h3 class="font-bold text-lg mb-2">Infos Gérant</h3>
            <p><strong>Nom :</strong> {{ $declaration->entreprise->gerant->nom }}</p>
            <p><strong>Prénoms :</strong> {{ $declaration->entreprise->gerant->prenoms }}</p>
            <p><strong>Contact :</strong> {{ $declaration->entreprise->gerant->contact }}</p>
        </div>

        <!-- Infos Entreprise -->
        <div class="bg-white shadow rounded p-4 mb-4">
            <h3 class="font-bold text-lg mb-2">Infos Entreprise</h3>
            <p><strong>Nom :</strong> {{ $declaration->entreprise->nom }}</p>
            <p><strong>RCCM :</strong> {{ $declaration->entreprise->rccm }}</p>
            <p><strong>Adresse :</strong> {{ $declaration->entreprise->adresse }}</p>
            <p><strong>Type :</strong> {{ $declaration->entreprise->type_entreprise }}</p>
            <p><strong>Secteur :</strong> {{ $declaration->entreprise->secteur_activite }}</p>
        </div>

        <!-- Infos Activité -->
        <div class="bg-white shadow rounded p-4 mb-4">
            <h3 class="font-bold text-lg mb-2">Infos Activité</h3>
            <p><strong>Nature :</strong> {{ $declaration->nature_activite }}</p>
            <p><strong>Secteur :</strong> {{ $declaration->secteur_activite }}</p>
            <p><strong>Produits :</strong> {{ $declaration->produits }}</p>
            <p><strong>Effectifs :</strong> {{ $declaration->effectifs }}</p>
        </div>

        <!-- Statut & Phase -->
        <div class="bg-white shadow rounded p-4">
            <h3 class="font-bold text-lg mb-2">Statut</h3>
            <p><strong>Statut :</strong> {{ $declaration->statut }}</p>
            <p><strong>Phase :</strong> {{ $declaration->phase_label }}</p>
        </div>

        <div class="mt-6 flex gap-3">

            <!-- 🔙 Bouton retour -->
            <a href="{{ route('declarations.index') }}" 
            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                Retour
            </a>

            <!-- 🚀 Bouton soumettre (si brouillon) -->
            @if($declaration->statut === 'brouillon')
                <a href="{{ route('declarations.edit', $declaration) }}" 
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">
                    Modifier
                </a>

                <form action="{{ route('declarations.submit', $declaration->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <button type="submit"
                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                        Soumettre
                    </button>
                </form>
            @endif

        </div>

    </div>
</x-app-layout>