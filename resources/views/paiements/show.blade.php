<x-app-layout>
<div class="max-w-3xl mx-auto mt-10 bg-white p-6 rounded shadow">

    <h2 class="text-xl font-bold mb-4">
        Paiement - {{ $declaration->reference }}
    </h2>

    @if(session('error'))
        <div class="bg-red-500 text-white p-3 mb-4 rounded">
            {{ session('error') }}
        </div>
    @endif

    <p class="mb-4">
        Montant : <strong>10 000 FCFA</strong>
    </p>

    @if($declaration->date_limite_paiement)
        <p class="mb-4 text-sm text-gray-600">
            Date limite : {{ \Carbon\Carbon::parse($declaration->date_limite_paiement)->format('d/m/Y') }}
        </p>
    @endif

    <form method="POST" action="{{ route('paiement.payer', $declaration) }}">
        @csrf

        <button class="bg-green-600 text-white px-4 py-2 rounded">
            Payer maintenant
        </button>
    </form>

</div>
</x-app-layout>