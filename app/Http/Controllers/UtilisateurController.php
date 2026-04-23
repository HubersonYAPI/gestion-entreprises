<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UtilisateurController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles')->latest();
 
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name',  'ilike', "%$s%")
                  ->orWhere('email', 'ilike', "%$s%");
            });
        }
 
        if ($request->filled('role')) {
            $query->whereHas('roles', fn($q) => $q->where('name', $request->role));
        }
 
        $users = $query->paginate(20)->withQueryString();
        $roles = Role::orderBy('name')->get();
 
        // Stats rapides
        $stats = [
            'total'       => User::count(),
            'gerants'     => User::role('GERANT')->count(),
            'agents'      => User::role('AGENT')->count(),
            'controleurs' => User::role('CONTROLEUR')->count(),
            'admins'      => User::role('SUPER_ADMIN')->count(),
        ];
 
        return view('agent.utilisateurs', compact('users', 'roles', 'stats'));
    }
 
    /**
     * Changer le rôle d'un utilisateur
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);
 
        // Empêcher de modifier son propre rôle
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas modifier votre propre rôle.');
        }
 
        $ancienRoles = $user->getRoleNames()->implode(', ');
 
        $user->syncRoles([$request->role]);
 
        // Log audit
        activity('utilisateurs')
            ->causedBy(Auth::user())
            ->performedOn($user)
            ->withProperties([
                'anciens_roles'   => $ancienRoles,
                'nouveau_role'    => $request->role,
                'utilisateur'     => $user->email,
            ])
            ->log('rôle utilisateur modifié');
 
        return back()->with('success', "Rôle de {$user->name} mis à jour → {$request->role}");
    }
 
    /**
     * Activer / désactiver un compte
     */
    public function toggleActive(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas désactiver votre propre compte.');
        }
 
        // Utilise un champ `active` booléen sur la table users (à ajouter si absent)
        $user->update(['active' => !$user->active]);
 
        $etat = $user->active ? 'activé' : 'désactivé';
 
        activity('utilisateurs')
            ->causedBy(Auth::user())
            ->performedOn($user)
            ->withProperties(['email' => $user->email, 'etat' => $etat])
            ->log("compte utilisateur {$etat}");
 
        return back()->with('success', "Compte de {$user->name} {$etat}.");
    }
 
    /**
     * Supprimer un utilisateur
     */
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }
 
        activity('utilisateurs')
            ->causedBy(Auth::user())
            ->performedOn($user)
            ->withProperties(['email' => $user->email, 'roles' => $user->getRoleNames()])
            ->log('utilisateur supprimé');
 
        $user->delete();
 
        return back()->with('success', "Utilisateur {$user->name} supprimé.");
    }
}
