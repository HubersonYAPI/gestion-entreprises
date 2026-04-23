<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Endpoint de polling — retourne count + 5 dernières non-lues en JSON
     * Appelé toutes les 30s par notification-bell.blade.php via fetch()
     */
    public function poll()
    {
        $user   = Auth::user();
        $unread = $user->unreadNotifications->take(5);

        $items = $unread->map(fn($n) => [
            'id'                    => $n->id,
            'action_label'          => $n->data['action_label']          ?? 'Notification',
            'declaration_reference' => $n->data['declaration_reference'] ?? '',
            'entreprise_nom'        => $n->data['entreprise_nom']        ?? '',
            'commentaire'           => $n->data['commentaire']           ?? '',
            'couleur'               => $n->data['couleur']               ?? 'gray',
            'read_url'              => route('notifications.markAsRead', $n->id),
            'created_at_human'      => $n->created_at->diffForHumans(),
        ]);

        return response()->json([
            'count' => $user->unreadNotifications->count(),
            'items' => $items->values(),
        ]);
    }

    /**
     * Marquer une notification comme lue et rediriger
     */
    public function markAsRead(string $id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        $storedUrl = $notification->data['url'] ?? null;

        if ($storedUrl) {
            $path  = parse_url($storedUrl, PHP_URL_PATH);
            $query = parse_url($storedUrl, PHP_URL_QUERY);
            return redirect($path . ($query ? '?' . $query : ''));
        }

        $fallback = Auth::user()->hasAnyRole(['AGENT', 'CONTROLEUR', 'SUPER_ADMIN'])
            ? route('agent.dashboard')
            : route('dashboard');

        return redirect($fallback);
    }

    /**
     * Marquer TOUTES comme lues
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Toutes les notifications ont été lues.');
    }

    /**
     * Page liste complète
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(20);
        return view('notifications.index', compact('notifications'));
    }

    /**
     * Supprimer
     */
    public function destroy(string $id)
    {
        Auth::user()->notifications()->findOrFail($id)->delete();
        return back()->with('success', 'Notification supprimée.');
    }
}