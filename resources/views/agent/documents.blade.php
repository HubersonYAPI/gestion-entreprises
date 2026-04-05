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
        
        <!-- LISTE -->
        <table class="w-full border">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="p-2">Type</th>
                    <th class="p-2">Statut</th>
                    <th class="p-2">Fichier</th>
                    <th class="p-2">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($documents as $doc)
                    <tr class="border-t">
                        <td class="p-2"> {{ $doc->type }} </td>
                        <td class="p-2"> {{ $doc->statut }} </td>
                        <td class="p-2">
                            <a href="{{ asset('storage/'.$doc->file_path) }}" 
                                target="_blank" title="Voir" 
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

        <div class="mt-6 flex gap-3">            
            <!-- 🔙 Bouton retour -->
            <a href="{{ route('agent.dashboard') }}" 
            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                Retour
            </a>
        </div>

    </div>
</x-app-layout>