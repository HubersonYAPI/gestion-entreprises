<x-app-layout>
<div class="max-w-6xl mx-auto mt-10 bg-white p-6 rounded shadow">

    <div class="p-6">
        <h2 class="text-2xl font-bold mb-4">
            Dashboard Agent
        </h2>

        @if (session('success'))
            <div class="bg-green-500 text-white p-3 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        <table class="w-full border">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="p-2">Référence</th>
                    <th class="p-2">Entreprise</th>
                    <th class="p-2">Nature Activité</th>
                    <th class="p-2">Secteur Activité</th>
                    <th class="p-2">Statut</th>
                    <th class="p-2">Phase</th>
                    <th class="p-2">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($declarations as $declaration)
                    <tr class="border-t">
                        <td class="p-2"> {{ $declaration->reference }} </td>
                        <td class="p-2"> {{ $declaration->entreprise->nom ?? '-'  }} </td>
                        <td class="p-2"> {{ $declaration->nature_activite }} </td>
                        <td class="p-2"> {{ $declaration->secteur_activite }} </td>
                        <td class="p-2"> {{ $declaration->statut }} </td>
                        <td class="p-2">{{ $declaration->phase_label  }}</td>
                        <td class="p-2 flex gap-2">
                            
                            <!-- 👁 Voir -->
                            <a href="{{ route('agent.declarations.show', $declaration) }}" title="Voir" class="text-blue-600 hover:text-blue-800">
                                <x-heroicon-o-eye class="w-5 h-5"/>
                            </a>
                            
                            <!-- 📄 Documents -->
                            <a href="{{ route('agent.declaration.documents', $declaration) }}" title="Documents" class="text-purple-600 hover:text-purple-800">
                                <x-heroicon-o-document-text class="w-5 h-5"/>
                            </a>

                            <!-- VALIDER -->
                            @if($declaration->statut === 'soumis')
                                <form action="{{ route('agent.valider', $declaration) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-green-500 hover:text-green-700 flex items-center gap-1">
                                        <x-heroicon-o-check class="w-5 h-5"/>
                                        <span>Valider</span>
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
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

</x-app-layout>