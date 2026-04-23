<x-agent-layout>
<x-slot name="pageTitle">Utilisateurs</x-slot>

<div class="pg">

    {{-- En-tête --}}
    <div class="pg-hd">
        <div>
            <div class="pg-title">Gestion des Utilisateurs</div>
            <div class="pg-ref">Gérez les comptes et les rôles de la plateforme</div>
        </div>
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

    {{-- Stats --}}
    <div class="stats">
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-blue">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                </div>
                <span class="sc-badge neu">Total</span>
            </div>
            <div class="sc-val">{{ $stats['total'] }}</div>
            <div class="sc-lbl">Utilisateurs</div>
        </div>
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-teal">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <span class="sc-badge neu">Rôle</span>
            </div>
            <div class="sc-val">{{ $stats['gerants'] }}</div>
            <div class="sc-lbl">Gérants</div>
        </div>
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-violet">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <span class="sc-badge neu">Rôle</span>
            </div>
            <div class="sc-val">{{ $stats['agents'] }}</div>
            <div class="sc-lbl">Agents</div>
        </div>
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-amber">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <span class="sc-badge neu">Rôle</span>
            </div>
            <div class="sc-val">{{ $stats['controleurs'] }}</div>
            <div class="sc-lbl">Contrôleurs</div>
        </div>
        <div class="sc">
            <div class="sc-top">
                <div class="sc-ico ic-red">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                </div>
                <span class="sc-badge neu">Rôle</span>
            </div>
            <div class="sc-val">{{ $stats['admins'] }}</div>
            <div class="sc-lbl">Super Admins</div>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="card">
        <div class="ch"><div class="ct">Recherche & Filtres</div></div>
        <form method="GET" action="{{ route('agent.admin.utilisateurs') }}"
              style="padding:.9rem 1.1rem;display:grid;grid-template-columns:1fr 200px auto;gap:.75rem;align-items:end;">
            <div>
                <label class="field-l">Recherche</label>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Nom, email…"
                    style="width:100%;padding:.45rem .7rem;border:1px solid var(--border);border-radius:7px;font-size:.8rem;color:var(--t1);background:#f8f9fd;outline:none;margin-top:.3rem;">
            </div>
            <div>
                <label class="field-l">Rôle</label>
                <select name="role" style="width:100%;padding:.45rem .7rem;border:1px solid var(--border);border-radius:7px;font-size:.8rem;color:var(--t1);background:#f8f9fd;outline:none;margin-top:.3rem;">
                    <option value="">Tous les rôles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex;gap:.5rem;">
                <button type="submit" style="padding:.46rem .9rem;border-radius:8px;border:none;background:var(--accent);color:#fff;font-size:.79rem;font-weight:700;cursor:pointer;">
                    Filtrer
                </button>
                @if(request()->hasAny(['search','role']))
                    <a href="{{ route('agent.admin.utilisateurs') }}"
                       style="padding:.46rem .9rem;border-radius:8px;border:1px solid var(--border);background:var(--white);color:var(--t2);font-size:.79rem;font-weight:600;text-decoration:none;display:flex;align-items:center;">
                        ✕
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="card">
        <div class="ch">
            <div class="ct">Liste des utilisateurs</div>
            <span class="cc">{{ $users->total() }} utilisateur(s)</span>
        </div>
        <div class="tw">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Utilisateur</th>
                        <th>Email</th>
                        <th>Rôle(s)</th>
                        <th>Statut</th>
                        <th>Inscription</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td style="font-size:.72rem;color:var(--t3);">{{ $user->id }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:.6rem;">
                                <div style="width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,#4f46e5,#7c3aed);display:flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:700;color:#fff;flex-shrink:0;">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div class="nm">{{ $user->name }}</div>
                            </div>
                        </td>
                        <td style="font-size:.78rem;color:var(--t2);">{{ $user->email }}</td>
                        <td>
                            <div style="display:flex;gap:.3rem;flex-wrap:wrap;">
                                @forelse($user->roles as $role)
                                    @php
                                        $roleColors = [
                                            'SUPER_ADMIN' => 'background:#fef2f2;color:#991b1b;',
                                            'AGENT'       => 'background:#eff6ff;color:#1d4ed8;',
                                            'CONTROLEUR'  => 'background:#fffbeb;color:#92400e;',
                                            'GERANT'      => 'background:#f0fdfa;color:#0f766e;',
                                        ];
                                        $style = $roleColors[$role->name] ?? 'background:#f1f5f9;color:#64748b;';
                                    @endphp
                                    <span style="font-size:.65rem;font-weight:700;padding:2px 8px;border-radius:20px;{{ $style }}">
                                        {{ $role->name }}
                                    </span>
                                @empty
                                    <span style="font-size:.72rem;color:var(--t3);">Aucun rôle</span>
                                @endforelse
                            </div>
                        </td>
                        <td>
                            @if($user->active ?? true)
                                <span class="bx b-valid">Actif</span>
                            @else
                                <span class="bx b-rej">Inactif</span>
                            @endif
                        </td>
                        <td style="font-size:.76rem;color:var(--t3);">
                            {{ $user->created_at->format('d/m/Y') }}
                        </td>
                        <td>
                            @if($user->id !== auth()->id())
                            <div class="acts">
                                {{-- Bouton changer rôle --}}
                                <button onclick="openRoleModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->roles->first()?->name }}')"
                                    class="bi" title="Changer le rôle"
                                    style="color:#7c3aed;border-color:#ddd6fe;background:#f5f3ff;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                                </button>

                                {{-- Toggle actif --}}
                                <form method="POST" action="{{ route('agent.admin.utilisateurs.toggle', $user) }}" style="display:inline;">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="bi" title="{{ ($user->active ?? true) ? 'Désactiver' : 'Activer' }}"
                                        style="{{ ($user->active ?? true) ? 'color:#d97706;border-color:#fde68a;background:#fffbeb;' : 'color:#059669;border-color:#a7f3d0;background:#ecfdf5;' }}">
                                        @if($user->active ?? true)
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                                        @else
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                        @endif
                                    </button>
                                </form>

                                {{-- Supprimer --}}
                                <form method="POST" action="{{ route('agent.admin.utilisateurs.destroy', $user) }}" style="display:inline;"
                                      onsubmit="return confirm('Supprimer {{ addslashes($user->name) }} ? Cette action est irréversible.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bi bi-rej" title="Supprimer">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                                    </button>
                                </form>
                            </div>
                            @else
                                <span style="font-size:.72rem;color:var(--t3);">— Vous —</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                Aucun utilisateur trouvé.
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="pager">{{ $users->links() }}</div>
        @endif
    </div>

</div>

{{-- ── Modal changement de rôle ────────────────────────────────────────── --}}
<div class="mo" id="roleModal">
    <div class="modal">
        <div class="mh">
            <div class="mh-l">
                <div class="m-ico" style="background:#f5f3ff;color:#7c3aed;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                </div>
                <div>
                    <div class="m-tit">Changer le rôle</div>
                    <div class="m-ref" id="roleModalSubtitle">—</div>
                </div>
            </div>
            <button class="m-cls" onclick="closeRoleModal()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form method="POST" id="roleForm">
            @csrf @method('PATCH')
            <div class="mb">
                <label class="m-lbl">Nouveau rôle</label>
                <select name="role" id="roleSelect"
                    style="width:100%;padding:.55rem .8rem;border:1px solid var(--border);border-radius:8px;font-size:.82rem;color:var(--t1);background:#f8f9fd;outline:none;">
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </select>
                <p class="m-hint">⚠️ Le changement de rôle prend effet immédiatement.</p>
            </div>
            <div class="mf">
                <button type="button" class="btn-cancel" onclick="closeRoleModal()">Annuler</button>
                <button type="submit" style="font-size:.79rem;font-weight:700;padding:.48rem 1.05rem;border-radius:8px;border:none;background:#7c3aed;color:#fff;cursor:pointer;">
                    Appliquer
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openRoleModal(userId, userName, currentRole) {
    document.getElementById('roleModalSubtitle').textContent = userName;
    document.getElementById('roleForm').action = `/agent/admin/utilisateurs/${userId}/role`;
    const sel = document.getElementById('roleSelect');
    if (currentRole) sel.value = currentRole;
    document.getElementById('roleModal').classList.add('op');
}
function closeRoleModal() {
    document.getElementById('roleModal').classList.remove('op');
}
document.getElementById('roleModal').addEventListener('click', function(e) {
    if (e.target === this) closeRoleModal();
});
</script>
@endpush

</x-agent-layout>
