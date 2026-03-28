<x-app-layout>
    <div class="max-w-5xl mx-auto mt-10 bg-white p-6 rounded shadow">

        <div class="flex justify-between mb-6">
            <h2 class="text-2xl font-bold">Mes Entreprises</h2>
            <a href="{{ route('entreprises.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">
                + Ajouter
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-500 text-white p-3 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        <table class="w-full border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2">Nom</th>
                    <th class="p-2">RCCM</th>
                    <th class="p-2">Adresse</th>
                    <th class="p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($entreprises as $entreprise)
                    <tr class="border-t">
                        <td class="p-2"> {{$entreprise->nom}} </td>
                        <td class="p-2"> {{$entreprise->rccm}} </td>
                        <td class="p-2"> {{$entreprise->adresse}} </td>
                        <td class="p-2 flex gap-2">                            
                            <!-- Modifier -->
                            <a href="{{ route('entreprises.edit', $entreprise) }}" title="Modifier" class="text-yellow-500 hover:text-yellow-700">
                                <x-heroicon-o-pencil-square class="w-5 h-5"/>
                            </a>

                            <form action="{{route('entreprises.destroy', $entreprise)}}" method="post">
                                @csrf
                                @method('DELETE')

                                {{-- <button class="text-red-500">Supprimer</button> --}}
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