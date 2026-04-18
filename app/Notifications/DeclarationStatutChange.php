<?php

namespace App\Notifications;

use App\Models\Declaration;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * DeclarationStatutChange
 *
 * Notification envoyée quand le statut d'une déclaration change.
 * Canaux : database (cloche en haut) + mail (email)
 *
 * Déclenchée pour :
 * - Le GÉRANT : quand un agent valide/rejette/traite sa déclaration
 * - Les ADMINS : quand un gérant soumet une nouvelle déclaration
 */
class DeclarationStatutChange extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly Declaration $declaration,
        public readonly string      $action,
        public readonly ?string     $commentaire = null,
        public readonly ?User       $declencheur = null,  // Qui a déclenché l'action
    ) {}

    /**
     * Canaux utilisés : base de données + email
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    // ── Canal DATABASE ────────────────────────────────────────

    /**
     * Données stockées en base (table notifications)
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'declaration_id'        => $this->declaration->id,
            'declaration_reference' => $this->declaration->reference,
            'entreprise_nom'        => $this->declaration->entreprise->nom ?? '—',
            'action'                => $this->action,
            'action_label'          => $this->getActionLabel(),
            'commentaire'           => $this->commentaire,
            'declencheur_nom'       => $this->declencheur?->name ?? 'Système',
            'url'                   => $this->getUrl($notifiable),
            'icone'                 => $this->getIcone(),
            'couleur'               => $this->getCouleur(),
        ];
    }

    // ── Canal EMAIL ───────────────────────────────────────────

    /**
     * Email envoyé au notifiable
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject($this->getEmailSubject())
            ->greeting("Bonjour {$notifiable->name},")
            ->line($this->getEmailLine())
            ->line("**Référence :** {$this->declaration->reference}")
            ->line("**Entreprise :** " . ($this->declaration->entreprise->nom ?? '—'));

        // Ajouter le motif si rejet
        if ($this->commentaire && $this->action === 'rejete') {
            $mail->line("**Motif du rejet :** {$this->commentaire}");
        }

        // Bouton d'action
        $mail->action($this->getEmailButtonLabel(), url($this->getUrl($notifiable)));

        // Pied de mail
        $mail->line('Connectez-vous à votre espace pour plus de détails.');

        return $mail;
    }

    // ── Helpers privés ────────────────────────────────────────

    private function getActionLabel(): string
    {
        $labels = [
            'soumis'           => '📋 Déclaration soumise',
            'approuve'         => '✅ Déclaration approuvée',
            'rejete'           => '❌ Déclaration rejetée',
            'en_traitement'    => '⏳ Déclaration en traitement',
            'paye'             => '💳 Paiement reçu',
            'valide'           => '✅ Déclaration validée',
            'document_valide'  => '✅ Document validé',
            'document_rejete'  => '❌ Document rejeté',
        ];
 
        return $labels[$this->action] ?? ucfirst($this->action);
    }

    private function getEmailSubject(): string
    {
        $ref = $this->declaration->reference;
 
        $subjects = [
            'soumis'           => "[Ges_Decl] Nouvelle déclaration soumise — {$ref}",
            'approuve'         => "[Ges_Decl] ✅ Déclaration approuvée — {$ref}",
            'rejete'           => "[Ges_Decl] ❌ Déclaration rejetée — {$ref}",
            'en_traitement'    => "[Ges_Decl] ⏳ Déclaration en traitement — {$ref}",
            'paye'             => "[Ges_Decl] 💳 Paiement reçu — {$ref}",
            'valide'           => "[Ges_Decl] ✅ Déclaration validée — {$ref}",
            'document_valide'  => "[Ges_Decl] Document validé — {$ref}",
            'document_rejete'  => "[Ges_Decl] Document rejeté — {$ref}",
        ];
 
        return $subjects[$this->action] ?? "[Ges_Decl] Mise à jour — {$ref}";
    }

    private function getEmailLine(): string
    {
        $lines = [
            'soumis'           => "Une nouvelle déclaration a été soumise et est en attente de traitement.",
            'approuve'           => "Votre déclaration a été **approuvée**. Vous devez maintenant procéder au paiement.",
            'rejete'           => "Votre déclaration a été **rejetée**. Consultez le motif ci-dessous.",
            'en_traitement'    => "Votre déclaration est en cours de traitement par notre équipe.",
            'paye'             => "Le paiement a été reçu. Votre dossier est en cours de finalisation.",
            'valide'          => "Votre déclaration est traitée avec succès. Votre attestation est disponible.",
            'document_valide'  => "Un document de votre dossier a été validé.",
            'document_rejete'  => "Un document de votre dossier a été rejeté. Veuillez en soumettre un nouveau.",
        ];
 
        return $lines[$this->action] ?? "Le statut de votre déclaration a été mis à jour.";
    }

    
    private function getEmailButtonLabel(): string
    {
        $labels = [
            'approuve'   => 'Procéder au paiement',
            'valide'  => "Télécharger l'attestation",
            'rejete'   => 'Voir les détails',
            'soumis'   => 'Voir la déclaration',
        ];
 
        return $labels[$this->action] ?? 'Voir la déclaration';
    }

    private function getUrl(object $notifiable): string
    {
        try {
            // Vérification du rôle — compatible avec les queues
            $isAdmin = $notifiable->roles()
                ->whereIn('name', ['AGENT', 'CONTROLEUR', 'SUPER_ADMIN'])
                ->exists();
 
            if ($isAdmin) {
                return route('agent.declarations.show', $this->declaration->id);
            }
        } catch (\Throwable $e) {
            // Si la relation échoue en queue, on retourne l'URL gérant par défaut
        }
 
        return route('declarations.show', $this->declaration->id);
    }
    
    private function getIcone(): string
    {
        $icons = [
            'approuve'         => 'check-circle',
            'rejete'           => 'x-circle',
            'en_traitement'    => 'clock',
            'soumis'           => 'file-text',
            'paye'             => 'credit-card',
            'valide'          => 'award',
            'document_valide'  => 'file-check',
            'document_rejete'  => 'file-x',
        ];
 
        return $icons[$this->action] ?? 'bell';
    }
    
    private function getCouleur(): string
    {
        $couleurs = [
            'approuve'         => 'green',
            'rejete'           => 'red',
            'en_traitement'    => 'yellow',
            'soumis'           => 'blue',
            'paye'             => 'teal',
            'valide'          => 'purple',
            'document_valide'  => 'green',
            'document_rejete'  => 'red',
        ];
 
        return $couleurs[$this->action] ?? 'gray';
    }
}