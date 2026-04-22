<x-agent-layout>
<x-slot name="pageTitle">Logs (Audit Trail)</x-slot>

<div class="pg">

    <div class="pg-hd">
        <div>
            <div class="pg-title">Logs — Audit Trail</div>
            <div class="pg-ref">Historique de toutes les actions effectuées sur la plateforme</div>
        </div>
    </div>

    {{-- Mini stats --}}
    <div class="stats-mini">
        <div class="sm">
            <div class="sm-ico ic-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
            <div>
                <div class="sm-val">{{ number_format($totalLogs) }}</div>
                <div class="sm-lbl">Total logs</div>
            </div>
        </div>
        <div class="sm">
            <div class="sm-ico ic-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
            <div>
                <div class="sm-val">{{ $logsAujourdhui }}</div>
                <div class="sm-lbl">Aujourd'hui</div>
            </div>
        </div>
        <div class="sm">
            <div class="sm-ico ic-violet"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
            <div>
                <div class="sm-val">{{ $utilisateursActifs }}</div>
                <div class="sm-lbl">Utilisateurs actifs aujourd'hui</div>
            </div>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="card">
        <div class="ch">
            <div class="ct">Filtres</div>
            @if(request()->hasAny(['action','user_id','date_debut','date_fin','search']))
                <a href="{{ route('agent.admin.logs') }}" style="font-size:.73rem;color:#dc2626;font-weight:600;text-decoration:none;">✕ Réinitialiser</a>
            @endif
        </div>
        <form method="GET" action="{{ route('agent.admin.logs') }}"
              style="padding:.9rem 1.1rem;display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:.75rem;">

            <div>
                <label class="field-l">Recherche</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Action, modèle, utilisateur…"
                    style="width:100%;padding:.45rem .7rem;border:1px solid var(--border);border-radius:7px;font-size:.8rem;color:var(--t1);background:#f8f9fd;outline:none;margin-top:.3rem;">
            </div>

            <div>
                <label class="field-l">Action</label>
                <select name="action" style="width:100%;padding:.45rem .7rem;border:1px solid var(--border);border-radius:7px;font-size:.8rem;color:var(--t1);background:#f8f9fd;outline:none;margin-top:.3rem;">
                    <option value="">Toutes</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>{{ $action }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="field-l">Utilisateur</label>
                <select name="user_id" style="width:100%;padding:.45rem .7rem;border:1px solid var(--border);border-radius:7px;font-size:.8rem;color:var(--t1);background:#f8f9fd;outline:none;margin-top:.3rem;">
                    <option value="">Tous</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="field-l">Date début</label>
                <input type="date" name="date_debut" value="{{ request('date_debut') }}"
                    style="width:100%;padding:.45rem .7rem;border:1px solid var(--border);border-radius:7px;font-size:.8rem;color:var(--t1);background:#f8f9fd;outline:none;margin-top:.3rem;">
            </div>

            <div>
                <label class="field-l">Date fin</label>
                <input type="date" name="date_fin" value="{{ request('date_fin') }}"
                    style="width:100%;padding:.45rem .7rem;border:1px solid var(--border);border-radius:7px;font-size:.8rem;color:var(--t1);background:#f8f9fd;outline:none;margin-top:.3rem;">
            </div>

            <div style="display:flex;align-items:flex-end;">
                <button type="submit" style="width:100%;padding:.46rem .9rem;border-radius:8px;border:none;background:var(--accent);color:#fff;font-size:.79rem;font-weight:700;cursor:pointer;">
                    Filtrer
                </button>
            </div>
        </form>
    </div>

    {{-- Table logs --}}
    <div class="card">
        <div class="ch">
            <div class="ct">Journal d'activité</div>
            <span class="cc">{{ $logs->total() }} entrée(s)</span>
        </div>
        <div class="tw">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date & Heure</th>
                        <th>Utilisateur</th>
                        <th>Action</th>
                        <th>Modèle</th>
                        <th>ID objet</th>
                        <th>Propriétés</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td style="font-size:.72rem;color:var(--t3);">{{ $log->id }}</td>
                        <td style="font-size:.76rem;white-space:nowrap;">
                            <span style="font-weight:600;color:var(--t1);">{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y') }}</span>
                            <span style="color:var(--t3);display:block;font-size:.7rem;">{{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s') }}</span>
                        </td>
                        <td>
                            @if($log->user_name)
                                <div style="display:flex;align-items:center;gap:.45rem;">
                                    <div style="width:24px;height:24px;border-radius:50%;background:linear-gradient(135deg,#4f46e5,#7c3aed);display:flex;align-items:center;justify-content:center;font-size:.6rem;font-weight:700;color:#fff;flex-shrink:0;">
                                        {{ strtoupper(substr($log->user_name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div style="font-size:.78rem;font-weight:600;color:var(--t1);">{{ $log->user_name }}</div>
                                        <div style="font-size:.68rem;color:var(--t3);">{{ $log->user_email }}</div>
                                    </div>
                                </div>
                            @else
                                <span style="font-size:.75rem;color:var(--t3);">Système</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $desc = strtolower($log->description ?? '');
                                $actCls = match(true) {
                                    str_contains($desc,'creat') || str_contains($desc,'store') => 'b-valid',
                                    str_contains($desc,'updat') || str_contains($desc,'edit')  => 'b-trait',
                                    str_contains($desc,'delet') || str_contains($desc,'rejet') => 'b-rej',
                                    str_contains($desc,'valid') || str_contains($desc,'appro') => 'b-np',
                                    default                                                     => 'b-def',
                                };
                            @endphp
                            <span class="bx {{ $actCls }}">{{ $log->description }}</span>
                        </td>
                        <td>
                            @if($log->subject_type)
                                <span style="font-size:.73rem;color:var(--t2);background:#f1f5f9;padding:2px 7px;border-radius:5px;font-family:monospace;">
                                    {{ class_basename($log->subject_type) }}
                                </span>
                            @else
                                <span style="color:var(--t3);font-size:.75rem;">—</span>
                            @endif
                        </td>
                        <td>
                            @if($log->subject_id)
                                <span class="ref">#{{ $log->subject_id }}</span>
                            @else
                                <span style="color:var(--t3);font-size:.75rem;">—</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $props = null;
                                if ($log->properties && $log->properties !== 'null') {
                                    try { $props = json_decode($log->properties, true); } catch (\Exception $e) {}
                                }
                            @endphp
                            @if($props && !empty($props))
                                <button onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='block'?'none':'block'"
                                    style="font-size:.7rem;color:var(--accent);background:#eff2ff;border:1px solid #c7d0f5;padding:2px 8px;border-radius:5px;cursor:pointer;font-weight:600;">
                                    Voir
                                </button>
                                <pre style="display:none;font-size:.67rem;color:var(--t2);background:#f8f9fd;border:1px solid var(--border);border-radius:6px;padding:.5rem;margin-top:.3rem;overflow:auto;max-width:260px;max-height:120px;">{{ json_encode($props, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            @else
                                <span style="color:var(--t3);font-size:.75rem;">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                Aucun log trouvé pour ces critères.
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
        <div class="pager">{{ $logs->links() }}</div>
        @endif
    </div>

</div>
</x-agent-layout>
