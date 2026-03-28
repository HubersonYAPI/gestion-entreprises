<x-app-layout>
<div class="max-w-6xl mx-auto mt-10 bg-white p-6 rounded shadow">

    <div class="flex justify-between mb-6">
        <h2 class="text-2xl font-bold">Mes déclarations</h2>
        <a href="{{ route('declarations.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">
            + Nouvelle
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-3 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full border">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-2 text-left">Référence</th>
                <th class="p-2 text-left">Entreprise</th>
                <th class="p-2 text-left">Statut</th>
                <th class="p-2 text-left">Phase</th>
                <th class="p-2 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($declarations as $declaration)
            <tr class="border-t">
                <td class="p-2">{{ $declaration->reference }}</td>
                <td class="p-2">{{ $declaration->entreprise->nom }}</td>
                <td class="p-2">{{ $declaration->statut }}</td>
                <td class="p-2">{{ $declaration->phase_label  }}</td>
                <td class="p-2 flex gap-2">
                    
                    <!-- 👁 Voir -->
                    <a href="{{ route('declarations.show', $declaration) }}" title="Voir" class="text-blue-600 hover:text-blue-800">
                        <x-heroicon-o-eye class="w-5 h-5"/>
                    </a>
                    
                    <!-- 📄 Documents -->
                    <a href="{{ route('documents.index', $declaration) }}" title="Documents" class="text-purple-600 hover:text-purple-800">
                        <x-heroicon-o-document-text class="w-5 h-5"/>
                    </a>

                    @if($declaration->statut === 'brouillon')

                        <!-- Modifier -->
                        <a href="{{ route('declarations.edit', $declaration) }}" title="Modifier" class="text-yellow-500 hover:text-yellow-700">
                            <x-heroicon-o-pencil-square class="w-5 h-5"/>
                        </a>

                        <!-- Soumettre -->
                        <form method="POST" action="{{ route('declarations.submit', $declaration) }}">
                            @csrf                            
                            <button type="submit" class="text-green-500 hover:text-green-700 flex items-center gap-1">
                                <x-heroicon-o-check class="w-5 h-5"/>
                                <span>Soumettre</span>
                            </button>
                        </form>

                        <!-- Supprimer -->
                        <form method="POST" action="{{ route('declarations.destroy', $declaration) }}">
                            @csrf
                            @method('DELETE')
                            <button title="Supprimer" class="text-red-500 hover:text-red-700">
                                <x-heroicon-o-trash class="w-5 h-5"/>
                            </button>
                        </form>
                    @endif

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
</x-app-layout>