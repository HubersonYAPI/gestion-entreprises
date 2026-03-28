<x-app-layout>
    <div class="max-w-3xl mx-auto mt-10 bg-white p-6 rounded shadow">

        <h2 class="text-2xl font-bold mb-6">Profil Gérant</h2>

        @if(session('success'))
            <div class="bg-green-500 p-3 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('gerant.update') }}" enctype="multipart/form-data">
            @csrf

            <!-- Nom -->
            <div class="mb-4">
                <label class="block font-semibold">Nom</label>
                <input type="text" name="nom" class="w-full border p-2 rounded"
                       value="{{ old('nom', $gerant->nom ?? '') }}" required>
            </div>

            <!-- Prénoms -->
            <div class="mb-4">
                <label class="block font-semibold">Prénoms</label>
                <input type="text" name="prenoms" class="w-full border p-2 rounded"
                       value="{{ old('prenoms', $gerant->prenoms ?? '') }}" required>
            </div>

            <!-- Contact -->
            <div class="mb-4">
                <label class="block font-semibold">Contact</label>
                <input type="text" name="contact" class="w-full border p-2 rounded"
                       value="{{ old('contact', $gerant->contact ?? '') }}" required>
            </div>

            <!-- Pièce -->
            <div class="mb-4">
                <label class="block font-semibold">Pièce d'identité</label>
                <input type="file" name="piece_identite" class="w-full border p-2 rounded">

                @if($gerant && $gerant->piece_identite)
                    <p class="mt-2">
                        <a href="{{ asset('storage/'.$gerant->piece_identite) }}" 
                            target="_blank" class="text-blue-500 hover:underline flex items-center gap-1">
                            <x-heroicon-o-eye class="w-5 h-5"/>
                            <span>Voir le fichier</span>
                        </a>
                    </p>
                @endif
            </div>

            <div class="mt-6 flex gap-3">
            
                <!-- 🔙 Bouton retour -->
                <a href="{{ route('gerant.show') }}" 
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    Retour
                </a>

                <button class="bg-blue-600 px-4 py-2 rounded">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</x-app-layout>