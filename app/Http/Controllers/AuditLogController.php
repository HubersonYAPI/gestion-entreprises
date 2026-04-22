<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        // On utilise la table activity_log de spatie/laravel-activitylog
        // Si tu n'as pas ce package, on utilise une table audit_logs custom (voir migration ci-dessous)
        $query = DB::table('activity_log')
            ->leftJoin('users', 'activity_log.causer_id', '=', 'users.id')
            ->select(
                'activity_log.*',
                'users.name as user_name',
                'users.email as user_email'
            )
            ->orderByDesc('activity_log.created_at');
 
        // ── Filtres ───────────────────────────────────────────────
        if ($request->filled('action')) {
            $query->where('activity_log.description', $request->action);
        }
        if ($request->filled('user_id')) {
            $query->where('activity_log.causer_id', $request->user_id);
        }
        if ($request->filled('date_debut')) {
            $query->whereDate('activity_log.created_at', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('activity_log.created_at', '<=', $request->date_fin);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('activity_log.description', 'ilike', "%$s%")
                  ->orWhere('activity_log.subject_type', 'ilike', "%$s%")
                  ->orWhere('users.name', 'ilike', "%$s%");
            });
        }
 
        $logs = $query->paginate(30)->withQueryString();
 
        // ── Listes pour les selects ───────────────────────────────
        $actions = DB::table('activity_log')
            ->select('description')
            ->distinct()
            ->orderBy('description')
            ->pluck('description');
 
        $users = DB::table('users')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();
 
        // ── Stats rapides ─────────────────────────────────────────
        $totalLogs    = DB::table('activity_log')->count();
        $logsAujourdhui = DB::table('activity_log')
            ->whereDate('created_at', today())
            ->count();
        $utilisateursActifs = DB::table('activity_log')
            ->whereDate('created_at', today())
            ->distinct('causer_id')
            ->count('causer_id');
 
        return view('agent.logs', compact(
            'logs', 'actions', 'users',
            'totalLogs', 'logsAujourdhui', 'utilisateursActifs'
        ));
    }
}
