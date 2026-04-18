<?php

namespace App\Services;

use App\Models\Declaration;
use App\Models\User;
use App\Notifications\DeclarationStatutChange;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Point d'entrée principal.
     * Appelé après chaque changement de statut.
     *
     * Actions gérant → notifie admins  : soumis, paye
     * Actions admin  → notifie gérant  : valide, approuve, rejete, en_traitement,
     *                                    document_valide, document_rejete
     */
    public static function notifier(
        Declaration $declaration,
        string      $action,
        ?string     $commentaire = null
    ): void {
        try {
            // Charger les relations nécessaires si absentes
            $declaration->loadMissing(['entreprise.gerant.user']);

            $declencheur = Auth::user();

            // ── Dispatch selon l'action ───────────────────────
            // IMPORTANT : on n'utilise PAS match() avec des virgules
            // pour les cas multiples (syntaxe fragile en PHP 8.0).
            // On utilise in_array() pour les groupes d'actions.

            $actionsAdmin = ['valide', 'approuve', 'rejete', 'en_traitement', 'document_valide', 'document_rejete'];
            $actionsUser  = ['soumis', 'paye'];

            if (in_array($action, $actionsUser)) {
                // Gérant agit → notifier tous les admins/agents
                self::notifierAdmins($declaration, $action, $commentaire, $declencheur);

            } elseif (in_array($action, $actionsAdmin)) {
                // Admin agit → notifier le gérant concerné
                self::notifierGerant($declaration, $action, $commentaire, $declencheur);

            } else {
                Log::warning("NotificationService: action inconnue '{$action}', aucune notification envoyée.");
            }

        } catch (\Throwable $e) {
            // Ne JAMAIS bloquer l'action métier à cause d'une erreur de notification
            Log::error('NotificationService::notifier() a échoué', [
                'declaration_id' => $declaration->id,
                'action'         => $action,
                'error'          => $e->getMessage(),
                'trace'          => $e->getTraceAsString(),
            ]);
        }
    }

    // ── Notifier le Gérant ────────────────────────────────────

    private static function notifierGerant(
        Declaration $declaration,
        string      $action,
        ?string     $commentaire,
        ?User       $declencheur
    ): void {
        $gerantUser = $declaration->entreprise?->gerant?->user;

        if (!$gerantUser) {
            Log::warning("NotificationService: gérant introuvable pour déclaration #{$declaration->id}");
            return;
        }

        // Ne pas notifier si le gérant est aussi l'acteur (rare mais possible)
        if ($declencheur && $gerantUser->id === $declencheur->id) {
            Log::info("NotificationService: gérant est aussi le déclencheur, skip.");
            return;
        }

        $gerantUser->notify(
            new DeclarationStatutChange($declaration, $action, $commentaire, $declencheur)
        );

        Log::info("✅ Notification envoyée au gérant #{$gerantUser->id} ({$gerantUser->email}) — action: {$action} — déclaration #{$declaration->id}");
    }

    // ── Notifier les Admins/Agents ────────────────────────────

    private static function notifierAdmins(
        Declaration $declaration,
        string      $action,
        ?string     $commentaire,
        ?User       $declencheur
    ): void {
        // Récupérer tous les utilisateurs admin
        $admins = User::role(['AGENT', 'CONTROLEUR', 'SUPER_ADMIN'])->get();

        if ($admins->isEmpty()) {
            Log::warning("NotificationService: aucun admin trouvé pour notifier.");
            return;
        }

        $count = 0;
        foreach ($admins as $admin) {
            // Ne pas notifier celui qui a déclenché l'action
            if ($declencheur && $admin->id === $declencheur->id) {
                continue;
            }

            $admin->notify(
                new DeclarationStatutChange($declaration, $action, $commentaire, $declencheur)
            );
            $count++;
        }

        Log::info("Notifications envoyées à {$count} admin(s) — action: {$action} — déclaration #{$declaration->id}");
    }
}