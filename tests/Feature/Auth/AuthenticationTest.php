<?php

/*
|--------------------------------------------------------------------------
| tests/Feature/Auth/AuthenticationTest.php
|--------------------------------------------------------------------------
| Routes testées :
|   GET  /login              → affichage formulaire
|   POST /login              → connexion
|   POST /logout             → déconnexion
|   GET  /dashboard          → protégée, redirige vers /login si invité
|   GET  /agent/dashboard    → protégée par rôle
|
| Logique de redirection après login (AuthenticatedSessionController) :
|   - Rôle AGENT|CONTROLEUR|SUPER_ADMIN → /agent/dashboard
|   - Rôle GERANT (ou aucun rôle admin) → /dashboard
|--------------------------------------------------------------------------
*/

use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Créer les rôles nécessaires s'ils n'existent pas
    Role::firstOrCreate(['name' => 'AGENT',       'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'CONTROLEUR',  'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'SUPER_ADMIN', 'guard_name' => 'web']);
});


// ── Test 1 : la page /login s'affiche ────────────────────────────────────
test('la page de connexion s\'affiche correctement', function () {

    $response = $this->get('/login');

    $response->assertOk();
});


// ── Test 2 : un gérant se connecte → redirigé vers /dashboard ────────────
test('un gérant est redirigé vers /dashboard après connexion', function () {

    $user = User::factory()->create([
        'password' => bcrypt('password123'),
    ]);
    // Pas de rôle admin → gérant

    $response = $this->post('/login', [
        'email'    => $user->email,
        'password' => 'password123',
    ]);

    // Redirection vers le dashboard gérant
    $response->assertRedirect('/dashboard');
    $this->assertAuthenticatedAs($user);
});


// ── Test 3 : un agent se connecte → redirigé vers /agent/dashboard ────────
test('un agent est redirigé vers /agent/dashboard après connexion', function () {

    $agent = User::factory()->create([
        'password' => bcrypt('password123'),
    ]);
    $agent->assignRole('AGENT');

    $response = $this->post('/login', [
        'email'    => $agent->email,
        'password' => 'password123',
    ]);

    $response->assertRedirect('/agent/dashboard');
    $this->assertAuthenticatedAs($agent);
});


// ── Test 4 : un SUPER_ADMIN se connecte → /agent/dashboard ───────────────
test('un super admin est redirigé vers /agent/dashboard après connexion', function () {

    $admin = User::factory()->create([
        'password' => bcrypt('password123'),
    ]);
    $admin->assignRole('SUPER_ADMIN');

    $this->post('/login', [
        'email'    => $admin->email,
        'password' => 'password123',
    ])->assertRedirect('/agent/dashboard');
});


// ── Test 5 : mauvais mot de passe → échec ────────────────────────────────
test('un mauvais mot de passe empêche la connexion', function () {

    $user = User::factory()->create([
        'password' => bcrypt('bon-mot-de-passe'),
    ]);

    $this->post('/login', [
        'email'    => $user->email,
        'password' => 'mauvais-mot-de-passe',
    ]);

    $this->assertGuest();
});


// ── Test 6 : déconnexion ─────────────────────────────────────────────────
test('un utilisateur connecté peut se déconnecter', function () {

    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $response->assertRedirect('/');
    $this->assertGuest();
});


// ── Test 7 : /dashboard protégé → redirige vers /login si invité ─────────
test('GET /dashboard redirige vers /login si non connecté', function () {

    $response = $this->get('/dashboard');

    $response->assertRedirect('/login');
});


// ── Test 8 : /agent/dashboard protégé → invité redirigé ──────────────────
test('GET /agent/dashboard redirige vers /login si non connecté', function () {

    $response = $this->get('/agent/dashboard');

    $response->assertRedirect('/login');
});


// ── Test 9 : /agent/dashboard interdit aux gérants ───────────────────────
test('un gérant ne peut pas accéder à /agent/dashboard (403 ou 302)', function () {

    $gerant = User::factory()->create();
    // Pas de rôle admin

    $response = $this->actingAs($gerant)->get('/agent/dashboard');

    expect($response->status())->toBeIn([302, 403]);
});
