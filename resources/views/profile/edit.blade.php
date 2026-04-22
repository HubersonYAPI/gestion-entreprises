{{--
    resources/views/profile/edit.blade.php
    Routeur de layout selon le rôle de l'utilisateur connecté.
--}}

@php
    $isAdmin = Auth::user()->hasAnyRole(['AGENT', 'CONTROLEUR', 'SUPER_ADMIN']);
@endphp

@if($isAdmin)
    <x-agent-layout>
        <x-slot name="pageTitle">Mon profil</x-slot>
        @include('profile._content_admin')
    </x-agent-layout>
@else
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Profile') }}
            </h2>
        </x-slot>
        @include('profile._content_gerant')
    </x-app-layout>
@endif