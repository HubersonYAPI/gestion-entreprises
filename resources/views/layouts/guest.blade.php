<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' — ' : '' }}{{ config('app.name', 'Ges_Decl') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">

    <!-- Scripts & Styles (Vite) -->
    @vite(['resources/css/app.css', 'resources/css/auth.css', 'resources/js/app.js'])
</head>
<body>
<div class="page-wrapper">

    <a href="/" class="brand">
        <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}">
        <span class="brand-name">Déclaration d'Activité Industrielle</span>
    </a>

    <div class="auth-card">
        {{ $slot }}
    </div>

</div>
</body>
</html>