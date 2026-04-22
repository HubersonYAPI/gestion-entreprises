{{--
    resources/views/notifications/index.blade.php

    Choisit automatiquement le layout selon le rôle :
    - Admin/Agent → x-agent-layout
    - Gérant      → x-app-layout
--}}

@php
    $isAdmin = Auth::user()->hasAnyRole(['AGENT', 'CONTROLEUR', 'SUPER_ADMIN']);
@endphp

@if($isAdmin)
<x-agent-layout>
    <x-slot name="pageTitle">Notifications</x-slot>
    @include('notifications._content_admin')
</x-agent-layout>
@else
<x-app-layout>
    @include('components.ui-styles')
    @include('notifications._content_gerant')
</x-app-layout>
@endif
