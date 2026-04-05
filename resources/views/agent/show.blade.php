<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Détail de la Déclaration : {{ $declaration->reference }} </h2>
    </x-slot>

    <div class="p-6">

        <div p-4 mb-4>
            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-2 rounded mb-2">
                    Document : {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 text-red-700 p-2 rounded mb-2">
                    Document : {{ session('error') }}
                </div>
            @endif
        </div>

        <!-- Référence -->
        <div class="bg-white shadow rounded p-4 mb-4">
            <h3 class="font-bold text-lg mb-2">Référence</h3>
            <p>{{ $declaration->reference }}</p>
        </div>

        <!-- Statut & Phase -->
        <div class="bg-white shadow rounded p-4 mb-4">
            <h3 class="font-bold text-lg mb-2">Statut</h3>
            <p><strong>Statut :</strong> {{ $declaration->statut }}</p>
            <p><strong>Phase :</strong> {{ $declaration->phase_label }}</p>
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

        <!-- 📄 Documents -->
        <div class="bg-white shadow rounded p-4 mt-6">
            <h3 class="font-bold text-lg mb-4">Documents</h3>

            @if($declaration->documents->isEmpty())
                <p class="text-gray-500">Aucun document ajouté.</p>
            @else
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
                        @foreach($declaration->documents as $doc)
                            <tr class="border-t">
                                <td class="p-2">{{ $doc->type }}</td>
                                <td class="p-2">{{ $doc->statut }}</td>
                                <td class="p-2">
                                    <a href="{{ asset('storage/'.$doc->file_path) }}" 
                                    target="_blank"
                                    class="text-blue-500 hover:text-blue-700 flex items-center gap-1">
                                        <x-heroicon-o-eye class="w-5 h-5"/>
                                        <span>Voir fichier</span>
                                    </a>
                                </td>
                                <td class="p-2 flex gap-2">

                                    <!-- VALIDER -->
                                    @if ($doc->statut != 'validé')
                                        <form action="{{ route('documents.valider', $doc) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-green-500 hover:text-green-700 flex items-center gap-1">
                                                <x-heroicon-s-check-circle class="w-5 h-5"/>
                                                <span>Valider</span>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- REJETER -->
                                    @if ($doc->statut != 'rejeté')                                        
                                        <form action="{{ route('documents.rejeter', $doc) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-red-500 hover:text-red-700 flex items-center gap-1">
                                                <x-heroicon-s-x-circle class="w-5 h-5"/>
                                                <span>Rejeter</span>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

        </div>
        
        

        

        <div class="mt-6 flex gap-3">

            <!-- 🔙 Bouton retour -->
            <a href="{{ route('agent.dashboard') }}" 
            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                Retour
            </a>

            <!-- VALIDER -->
            @if($declaration->statut === 'soumis')
                <form action="{{ route('agent.valider', $declaration) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                        Valider
                    </button>
                </form>

                <!-- REJETER -->
                <form method="POST" action="{{ route('agent.rejeter', $declaration) }}">
                    @csrf
                    <input type="text" name="commentaire" placeholder="Motif rejet" class="border p-2 mr-2">
                    <button class="bg-red-600 text-white px-4 py-2 rounded">
                        Rejeter
                    </button>
                </form>
            @endif

        </div>

    </div>
</x-app-layout>