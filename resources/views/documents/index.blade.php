<x-app-layout>
    <div class="max-w-5xl mx-auto mt-10 bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-4">
            Documents - {{ $declaration->reference }}
        </h2>

        @if (session('success'))
            <div>
                {{ session('success') }}
            </div>
        @endif

        <!-- 🏢 Infos Entreprise -->
        <div class="bg-gray-50 border rounded p-4 mb-6">
            <h3 class="font-bold text-lg mb-2">Informations de l'entreprise</h3>

            <div class="grid grid-cols-2 gap-4">
                <p><strong>Nom :</strong> {{ $declaration->entreprise->nom }}</p>
                <p><strong>RCCM :</strong> {{ $declaration->entreprise->rccm }}</p>

                <p><strong>Adresse :</strong> {{ $declaration->entreprise->adresse }}</p>
                <p><strong>Type :</strong> {{ $declaration->entreprise->type_entreprise }}</p>

                <p><strong>Secteur :</strong> {{ $declaration->entreprise->secteur_activite }}</p>
            </div>
        </div>            

        <!-- FORMULAIRE -->
        <form action="{{ route('documents.store', $declaration) }}" method="post" enctype="multipart/form-data" class="mb-6">
            @csrf

            <select name="type" class="border p-2 mb-2 w-full">
                <option value="RCCM">RCCM</option>
                <option value="CC">Compte Contribuable</option>
                <option value="produits">Liste Produits</option>
                <option value="appareils">Liste Appareils</option>
                <option value="formulaire">Formulaire Signé</option>
            </select>

            <input type="file" name="file" class="border p-2 mb-2 w-full">

            <div class="mt-6 flex gap-3">            
                <!-- 🔙 Bouton retour -->
                <a href="{{ route('declarations.index') }}" 
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    Retour
                </a>

                <button class="bg-blue-600 text-white px-4 py-2 rounded">
                    Ajouter
                </button>
            </div>
        </form>

        <!-- LISTE -->
        <table class="w-full border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2 text-left">Type</th>
                    <th class="p-2 text-left">Statut</th>
                    <th class="p-2 text-left">Fichier</th>
                    <th class="p-2 text-left">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($documents as $doc)
                    <tr class="border-t">
                        <td class="p-2"> {{ $doc->type }} </td>
                        <td class="p-2"> {{ $doc->statut }} </td>
                        <td class="p-2">
                            <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank" title="Voir" class="text-blue-600 hover:text-blue-800">
                                <x-heroicon-o-eye class="w-5 h-5"/> Voir
                            </a>
                        </td>
                        <td class="p-2">
                            <form method="POST" action="{{ route('documents.destroy', $doc) }}">
                                @csrf
                                @method('DELETE')
                                <button title="Supprimer" class="text-red-500 hover:text-red-700">
                                    <x-heroicon-o-trash class="w-5 h-5"/>
                                </button>
                            </form>
                        </td>
                    </tr>                    
                @endforeach
            </tbody>
        </table>

    </div>
</x-app-layout>