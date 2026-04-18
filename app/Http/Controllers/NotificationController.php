<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Marquer une notification comme lue et rediriger
     */
    public function markAsRead(string $id)
    {
        $notification = Auth::user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        // L'URL stockée en base est une URL absolue générée au moment de l'envoi
        // (ex: http://localhost:8000/agent/declarations/11).
        // On extrait uniquement le PATH pour éviter les problèmes de port/domaine
        // entre le moment où la notif a été créée et celui où on clique dessus.
        $storedUrl = $notification->data['url'] ?? null;

        if ($storedUrl) {
            $path = parse_url($storedUrl, PHP_URL_PATH);

            // Récupérer aussi le query string s'il existe
            $query = parse_url($storedUrl, PHP_URL_QUERY);
            $redirectPath = $path . ($query ? '?' . $query : '');

            return redirect($redirectPath);
        }

        // Fallback : dashboard selon le rôle
        $user = Auth::user();
        $fallback = $user->hasAnyRole(['AGENT', 'CONTROLEUR', 'SUPER_ADMIN'])
            ? route('agent.dashboard')
            : route('dashboard');

        return redirect($fallback);
    }

    /**
     * Marquer TOUTES les notifications comme lues
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Toutes les notifications ont été lues.');
    }

    /**
     * Liste de toutes les notifications (page dédiée)
     */
    public function index()
    {
        $notifications = Auth::user()
            ->notifications()
            ->latest()
            ->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Supprimer une notification
     */
    public function destroy(string $id)
    {
        Auth::user()
            ->notifications()
            ->findOrFail($id)
            ->delete();

        return back()->with('success', 'Notification supprimée.');
    }
}