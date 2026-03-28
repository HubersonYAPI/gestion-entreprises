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
                <th class="p-2">Référence</th>
                <th class="p-2">Entreprise</th>
                <th class="p-2">Statut</th>
                <th class="p-2">Phase</th>
                <th class="p-2">Actions</th>
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
                    
                    <a href="{{ route('declarations.show', $declaration) }}" class="text-blue-500">Voir</a>
                    

                    @if($declaration->statut === 'brouillon')
                        <a href="{{ route('declarations.edit', $declaration) }}" class="text-yellow-500">Modifier</a>

                        <form method="POST" action="{{ route('declarations.submit', $declaration) }}">
                            @csrf                            
                            <button class="text-green-600">Soumettre</button>
                        </form>

                        <form method="POST" action="{{ route('declarations.destroy', $declaration) }}">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-500">Supprimer</button>
                    </form>
                    @endif

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
</x-app-layout>