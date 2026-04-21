<?php

use Database\Seeders\RoleSeeder;
use Spatie\Permission\Models\Role;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

describe('RoleSeeder', function () {

    it('crée les 4 rôles attendus', function () {
        $this->seed(RoleSeeder::class);

        foreach (['GERANT', 'AGENT', 'CONTROLEUR', 'SUPER_ADMIN'] as $role) {
            expect(Role::where('name', $role)->where('guard_name', 'web')->exists())
                ->toBeTrue("Le rôle $role devrait exister");
        }
    });

    it('est idempotent — ne duplique pas les rôles', function () {
        $this->seed(RoleSeeder::class);
        $this->seed(RoleSeeder::class);

        expect(Role::where('guard_name', 'web')->count())->toBe(4);
    });

    it('attribue le guard web à tous les rôles', function () {
        $this->seed(RoleSeeder::class);

        Role::all()->each(fn ($role) =>
            expect($role->guard_name)->toBe('web')
        );
    });
});
