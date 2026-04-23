<x-agent-layout>
<x-slot name="pageTitle">Rôles & Permissions</x-slot>

<div class="pg">

    {{-- En-tête --}}
    <div class="pg-hd">
        <div>
            <div class="pg-title">Rôles & Permissions</div>
            <div class="pg-ref">Gérez les rôles de la plateforme et leurs droits d'accès</div>
        </div>
        <button onclick="document.getElementById('createRoleModal').classList.add('op')"
            style="display:inline-flex;align-items:center;gap:.4rem;font-size:.79rem;font-weight:700;padding:.46rem .9rem;border-radius:8px;border:none;background:var(--accent);color:#fff;cursor:pointer;">
            <svg style="width:13px;height:13px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nouveau rôle
        </button>
    </div>

    {{-- Alertes --}}
    @if(session('success'))
        <div class="a-ok">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="a-err">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Cards de rôles --}}
    <div class="three">
        @foreach($roles as $role)
        @php
            $rolesSysteme = ['SUPER_ADMIN', 'AGENT', 'CONTROLEUR', 'GERANT'];
            $estSysteme   = in_array($role->name, $rolesSysteme);
            $roleIcons = [
                'SUPER_ADMIN' => ['bg' => '#fef2f2', 'color' => '#dc2626', 'icon' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>'],
                'AGENT'       => ['bg' => '#eff6ff', 'color' => '#2563eb', 'icon' => '<path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>'],
                'CONTROLEUR'  => ['bg' => '#fffbeb', 'color' => '#d97706', 'icon' => '<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>'],
                'GERANT'      => ['bg' => '#f0fdfa', 'color' => '#0f766e', 'icon' => '<path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><path d="M9 22V12h6v10"/>'],
            ];
            $ri = $roleIcons[$role->name] ?? ['bg' => '#f5f3ff', 'color' => '#7c3aed', 'icon' => '<rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>'];
        @endphp
        <div class="card">
            <div class="ch">
                <div style="display:flex;align-items:center;gap:.6rem;">
                    <div style="width:32px;height:32px;border-radius:8px;background:{{ $ri['bg'] }};color:{{ $ri['color'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg style="width:15px;height:15px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">{!! $ri['icon'] !!}</svg>
                    </div>
                    <div>
                        <div class="ct">{{ $role->name }}</div>
                        <div style="font-size:.68rem;color:var(--t3);margin-top:.05rem;">
                            {{ $role->users_count }} utilisateur(s)
                            @if($estSysteme)
                                · <span style="color:#d97706;font-weight:600;">Système</span>
                            @endif
                        </div>
                    </div>
                </div>
                @if(!$estSysteme && $role->users_count === 0)
                    <form method="POST" action="{{ route('agent.admin.roles.destroy', $role) }}"
                          onsubmit="return confirm('Supprimer le rôle {{ $role->name }} ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="bi bi-rej" title="Supprimer" style="width:24px;height:24px;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>
                        </button>
                    </form>
                @endif
            </div>

            {{-- Permissions du rôle --}}
            <form method="POST" action="{{ route('agent.admin.roles.permissions', $role) }}">
                @csrf @method('PATCH')
                <div style="padding:.85rem 1.1rem;display:flex;flex-direction:column;gap:.5rem;max-height:360px;overflow-y:auto;">

                    @if($permissionsGroupees->isEmpty())
                        <p style="font-size:.76rem;color:var(--t3);text-align:center;padding:1rem 0;">
                            Aucune permission définie.<br>
                            <span style="font-size:.7rem;">Lancez <code>php artisan permission:setup</code></span>
                        </p>
                    @else
                        @foreach($permissionsGroupees as $groupe => $perms)
                        <div>
                            <div style="font-size:.6rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--t3);margin-bottom:.35rem;padding-bottom:.2rem;border-bottom:1px solid var(--border);">
                                {{ $groupe }}
                            </div>
                            <div style="display:flex;flex-direction:column;gap:.2rem;">
                                @foreach($perms as $perm)
                                <label style="display:flex;align-items:center;gap:.5rem;font-size:.76rem;color:var(--t2);cursor:pointer;padding:.2rem .3rem;border-radius:5px;transition:background .1s;"
                                       onmouseover="this.style.background='#f8f9fd'" onmouseout="this.style.background='transparent'">
                                    <input type="checkbox"
                                        name="permissions[]"
                                        value="{{ $perm->name }}"
                                        {{ $role->hasPermissionTo($perm->name) ? 'checked' : '' }}
                                        style="accent-color:var(--accent);width:14px;height:14px;cursor:pointer;">
                                    <span style="font-family:monospace;font-size:.72rem;">{{ $perm->name }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>

                @if(!$permissionsGroupees->isEmpty())
                <div style="padding:.7rem 1.1rem;border-top:1px solid var(--border);background:#fafbff;">
                    <button type="submit" class="bv" style="width:100%;justify-content:center;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Enregistrer les permissions
                    </button>
                </div>
                @endif
            </form>
        </div>
        @endforeach
    </div>

    {{-- Tableau récap permissions x rôles --}}
    @if(!$permissionsGroupees->isEmpty())
    <div class="card">
        <div class="ch">
            <div class="ct">Matrice des permissions</div>
            <span class="cc">{{ $permissions->count() }} permissions · {{ $roles->count() }} rôles</span>
        </div>
        <div class="tw">
            <table>
                <thead>
                    <tr>
                        <th style="min-width:180px;">Permission</th>
                        @foreach($roles as $role)
                            <th style="text-align:center;">{{ $role->name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($permissionsGroupees as $groupe => $perms)
                    <tr>
                        <td colspan="{{ $roles->count() + 1 }}"
                            style="background:#f8f9fd;font-size:.6rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--t3);padding:.4rem 1.1rem;">
                            {{ $groupe }}
                        </td>
                    </tr>
                    @foreach($perms as $perm)
                    <tr>
                        <td>
                            <span style="font-family:monospace;font-size:.73rem;color:var(--t1);">{{ $perm->name }}</span>
                        </td>
                        @foreach($roles as $role)
                        <td style="text-align:center;">
                            @if($role->hasPermissionTo($perm->name))
                                <svg style="width:14px;height:14px;color:#059669;display:inline-block;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                            @else
                                <span style="color:var(--border);font-size:.9rem;">—</span>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>

{{-- ── Modal créer un rôle ──────────────────────────────────────────────── --}}
<div class="mo" id="createRoleModal">
    <div class="modal">
        <div class="mh">
            <div class="mh-l">
                <div class="m-ico" style="background:#f5f3ff;color:#7c3aed;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                </div>
                <div>
                    <div class="m-tit">Nouveau rôle</div>
                    <div class="m-ref">Le nom sera converti en majuscules</div>
                </div>
            </div>
            <button class="m-cls" onclick="document.getElementById('createRoleModal').classList.remove('op')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('agent.admin.roles.store') }}">
            @csrf
            <div class="mb">
                <label class="m-lbl">Nom du rôle</label>
                <input type="text" name="name" placeholder="Ex: SUPERVISEUR"
                    style="width:100%;padding:.6rem .8rem;border:1px solid var(--border);border-radius:8px;font-size:.8rem;font-family:monospace;color:var(--t1);background:#f8f9fd;outline:none;"
                    required>
                <p class="m-hint">Les rôles système (AGENT, GERANT, etc.) ne peuvent pas être supprimés.</p>
            </div>
            <div class="mf">
                <button type="button" class="btn-cancel"
                    onclick="document.getElementById('createRoleModal').classList.remove('op')">Annuler</button>
                <button type="submit"
                    style="font-size:.79rem;font-weight:700;padding:.48rem 1.05rem;border-radius:8px;border:none;background:#7c3aed;color:#fff;cursor:pointer;">
                    Créer le rôle
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Fermer modals sur clic extérieur
document.getElementById('createRoleModal').addEventListener('click', function(e) {
    if (e.target === this) this.classList.remove('op');
});
</script>
@endpush

</x-agent-layout>
