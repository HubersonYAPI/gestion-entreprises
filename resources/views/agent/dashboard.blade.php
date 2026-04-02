<x-app-layout>

    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">
            Dashboard Agent
        </h1>

        <table class="w-full border">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th>ID</th>
                    <th>Entreprise</th>
                    <th>Statut</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($declarations as $declaration)
                    <tr>
                        <td class="p-2"> {{ $declaration->id }} </td>
                        <td> {{ $declaration->entreprise->nom ?? '-'  }} </td>
                        <td> {{ $declaration->statut }} </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</x-app-layout>