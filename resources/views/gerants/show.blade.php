<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold mb-4">Profil du Gérant</h2>
    </x-slot>

    <div class="p-6">    

        <div class="bg-white p-4 rounded shadow">
            <p><strong>Nom :</strong> {{ $gerant->nom }}</p>
            <p><strong>Prénoms :</strong> {{ $gerant->prenoms }}</p>
            <p><strong>Contact :</strong> {{ $gerant->contact }}</p>

            @if($gerant->piece_identite)
                <p class="mt-2"><strong>Pièce d'identité :</strong></p>
                <a href="{{ asset('storage/'.$gerant->piece_identite) }}" 
                target="_blank"
                class="text-blue-500 hover:underline flex items-center gap-1">
                    <x-heroicon-o-eye class="w-5 h-5"/>
                    <span>Voir le fichier</span>
                </a>
            @endif
        </div>

        <div class="mt-4">
            <a href="{{ route('gerant.edit') }}" 
                class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">
                Modifier le profil
            </a>
        </div>

    </div>
</x-app-layout>