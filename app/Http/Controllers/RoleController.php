<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')
            ->withCount('users')
            ->orderBy('name')
            ->get();

        $permissions = Permission::orderBy('name')->get();

        // Grouper les permissions par préfixe (ex: declarations.*, documents.*)
        $permissionsGroupees = $permissions->groupBy(function ($p) {
            return explode('.', $p->name)[0] ?? 'autre';
        });

        return view('agent.roles', compact('roles', 'permissions', 'permissionsGroupees'));
    }

    /**
     * Créer un nouveau rôle
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name|max:50',
        ]);

        $role = Role::create(['name' => strtoupper(trim($request->name))]);

        if ($request->filled('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        activity('roles')
            ->causedBy(Auth::user())
            ->withProperties(['role' => $role->name])
            ->log('rôle créé');

        return back()->with('success', "Rôle {$role->name} créé.");
    }

    /**
     * Mettre à jour les permissions d'un rôle
     */
    public function updatePermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions'   => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $anciennesPermissions = $role->permissions->pluck('name')->toArray();

        $role->syncPermissions($request->permissions ?? []);

        activity('roles')
            ->causedBy(Auth::user())
            ->performedOn($role)
            ->withProperties([
                'role'              => $role->name,
                'avant'             => $anciennesPermissions,
                'apres'             => $request->permissions ?? [],
            ])
            ->log('permissions rôle mises à jour');

        return back()->with('success', "Permissions du rôle {$role->name} mises à jour.");
    }

    /**
     * Supprimer un rôle (protection des rôles système)
     */
    public function destroy(Role $role)
    {
        $rolesSysteme = ['SUPER_ADMIN', 'AGENT', 'CONTROLEUR', 'GERANT'];

        if (in_array($role->name, $rolesSysteme)) {
            return back()->with('error', "Le rôle système « {$role->name} » ne peut pas être supprimé.");
        }

        if ($role->users()->count() > 0) {
            return back()->with('error', "Impossible de supprimer : {$role->users()->count()} utilisateur(s) ont ce rôle.");
        }

        activity('roles')
            ->causedBy(Auth::user())
            ->withProperties(['role' => $role->name])
            ->log('rôle supprimé');

        $role->delete();

        return back()->with('success', "Rôle {$role->name} supprimé.");
    }
}