<?php

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

describe('UserSeeder', function () {

    beforeEach(function () {
        $this->seed(RoleSeeder::class);
    });

    it('crée les 4 comptes de test', function () {
        $this->seed(UserSeeder::class);

        expect(User::count())->toBe(4);
    });

    it('crée le super admin avec le bon rôle', function () {
        $this->seed(UserSeeder::class);

        $admin = User::where('email', 'admin@test.com')->first();
        expect($admin)->not->toBeNull();
        expect($admin->hasRole('SUPER_ADMIN'))->toBeTrue();
    });

    it('crée l\'agent avec le bon rôle', function () {
        $this->seed(UserSeeder::class);

        $agent = User::where('email', 'agent@test.com')->first();
        expect($agent)->not->toBeNull();
        expect($agent->hasRole('AGENT'))->toBeTrue();
    });

    it('crée le contrôleur avec le bon rôle', function () {
        $this->seed(UserSeeder::class);

        $controleur = User::where('email', 'controleur@test.com')->first();
        expect($controleur)->not->toBeNull();
        expect($controleur->hasRole('CONTROLEUR'))->toBeTrue();
    });

    it('crée le gérant avec le bon rôle', function () {
        $this->seed(UserSeeder::class);

        $gerant = User::where('email', 'gerant@test.com')->first();
        expect($gerant)->not->toBeNull();
        expect($gerant->hasRole('GERANT'))->toBeTrue();
    });

    it('est idempotent — ne duplique pas les utilisateurs', function () {
        $this->seed(UserSeeder::class);
        $this->seed(UserSeeder::class);

        expect(User::where('email', 'admin@test.com')->count())->toBe(1);
        expect(User::count())->toBe(4);
    });

    it('les mots de passe sont hachés', function () {
        $this->seed(UserSeeder::class);

        $user = User::where('email', 'admin@test.com')->first();
        expect(\Illuminate\Support\Facades\Hash::check('Admin1234', $user->password))->toBeTrue();
    });
});
