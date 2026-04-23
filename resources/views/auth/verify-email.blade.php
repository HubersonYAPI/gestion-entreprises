<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __("Merci pour votre inscription ! Vérifiez votre e-mail via le lien envoyé. Besoin d’un nouveau lien ?") }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ __("Un nouveau lien de vérification a été envoyé à votre e-mail.") }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('Renvoyer Email de vérification') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('Deconnexion') }}
            </button>
        </form>
    </div>
</x-guest-layout>
