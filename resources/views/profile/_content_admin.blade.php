{{--
    resources/views/profile/_content_admin.blade.php
    Vue profil pour Admin/Agent/Contrôleur — utilise exclusivement admin.css.
--}}

<div class="pg">

    {{-- ── En-tête de page ─────────────────────────────────────────────── --}}
    <div class="pg-hd">
        <div>
            <div class="pg-title">Mon profil</div>
            <div class="pg-ref">{{ Auth::user()->email }}</div>
        </div>
    </div>

    {{-- ── Alertes session ──────────────────────────────────────────────── --}}
    @if(session('status') === 'profile-updated')
    <div class="a-ok">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <polyline points="20 6 9 17 4 12"/>
        </svg>
        Profil mis à jour avec succès.
    </div>
    @endif

    @if(session('status') === 'password-updated')
    <div class="a-ok">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <polyline points="20 6 9 17 4 12"/>
        </svg>
        Mot de passe mis à jour avec succès.
    </div>
    @endif

    <div class="two">

        {{-- ── Card : Informations du profil ───────────────────────────── --}}
        <div class="card">
            <div class="ch">
                <div style="display:flex;align-items:center;gap:.6rem">
                    <div class="ic-hico" style="background:#eff6ff;color:#2563eb">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>
                    <span class="ct">Informations du compte</span>
                </div>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" style="padding:1.25rem;display:flex;flex-direction:column;gap:1rem">
                @csrf
                @method('PATCH')

                {{-- Nom --}}
                <div class="field">
                    <label class="field-l" for="name">Nom complet</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        required
                        style="width:100%;padding:.55rem .8rem;border:1px solid var(--border);border-radius:8px;font-size:.84rem;font-family:inherit;color:var(--t1);background:#fff;outline:none;transition:border-color .15s"
                        onfocus="this.style.borderColor='var(--accent)'"
                        onblur="this.style.borderColor='var(--border)'"
                    >
                    @error('name')
                    <span style="font-size:.72rem;color:#dc2626">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="field">
                    <label class="field-l" for="email">Adresse e-mail</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        required
                        style="width:100%;padding:.55rem .8rem;border:1px solid var(--border);border-radius:8px;font-size:.84rem;font-family:inherit;color:var(--t1);background:#fff;outline:none;transition:border-color .15s"
                        onfocus="this.style.borderColor='var(--accent)'"
                        onblur="this.style.borderColor='var(--border)'"
                    >
                    @error('email')
                    <span style="font-size:.72rem;color:#dc2626">{{ $message }}</span>
                    @enderror
                </div>

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div style="font-size:.78rem;color:var(--t2);background:#fffbeb;border:1px solid #fcd34d;border-radius:8px;padding:.65rem .85rem">
                    Votre adresse e-mail n'est pas vérifiée.
                    <button form="send-verification" style="color:#2563eb;background:none;border:none;cursor:pointer;font-size:.78rem;text-decoration:underline;padding:0">
                        Renvoyer l'e-mail de vérification
                    </button>
                </div>
                @if (session('status') === 'verification-link-sent')
                <div style="font-size:.78rem;color:#059669">
                    Un nouveau lien de vérification a été envoyé.
                </div>
                @endif
                @endif

                <div class="abar" style="padding:.75rem 0 0;border-top:1px solid var(--border);margin-top:.25rem">
                    <button type="submit" class="btn-ok2">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>

        {{-- ── Card : Changer le mot de passe ──────────────────────────── --}}
        <div class="card">
            <div class="ch">
                <div style="display:flex;align-items:center;gap:.6rem">
                    <div class="ic-hico" style="background:#f5f3ff;color:#7c3aed">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0110 0v4"/>
                        </svg>
                    </div>
                    <span class="ct">Changer le mot de passe</span>
                </div>
            </div>

            <form method="POST" action="{{ route('password.update') }}" style="padding:1.25rem;display:flex;flex-direction:column;gap:1rem">
                @csrf
                @method('PUT')

                {{-- Mot de passe actuel --}}
                <div class="field">
                    <label class="field-l" for="current_password">Mot de passe actuel</label>
                    <input
                        type="password"
                        id="current_password"
                        name="current_password"
                        autocomplete="current-password"
                        style="width:100%;padding:.55rem .8rem;border:1px solid var(--border);border-radius:8px;font-size:.84rem;font-family:inherit;color:var(--t1);background:#fff;outline:none;transition:border-color .15s"
                        onfocus="this.style.borderColor='var(--accent)'"
                        onblur="this.style.borderColor='var(--border)'"
                    >
                    @error('current_password', 'updatePassword')
                    <span style="font-size:.72rem;color:#dc2626">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Nouveau mot de passe --}}
                <div class="field">
                    <label class="field-l" for="password">Nouveau mot de passe</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        autocomplete="new-password"
                        style="width:100%;padding:.55rem .8rem;border:1px solid var(--border);border-radius:8px;font-size:.84rem;font-family:inherit;color:var(--t1);background:#fff;outline:none;transition:border-color .15s"
                        onfocus="this.style.borderColor='var(--accent)'"
                        onblur="this.style.borderColor='var(--border)'"
                    >
                    @error('password', 'updatePassword')
                    <span style="font-size:.72rem;color:#dc2626">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Confirmation --}}
                <div class="field">
                    <label class="field-l" for="password_confirmation">Confirmer le mot de passe</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        autocomplete="new-password"
                        style="width:100%;padding:.55rem .8rem;border:1px solid var(--border);border-radius:8px;font-size:.84rem;font-family:inherit;color:var(--t1);background:#fff;outline:none;transition:border-color .15s"
                        onfocus="this.style.borderColor='var(--accent)'"
                        onblur="this.style.borderColor='var(--border)'"
                    >
                    @error('password_confirmation', 'updatePassword')
                    <span style="font-size:.72rem;color:#dc2626">{{ $message }}</span>
                    @enderror
                </div>

                <div class="abar" style="padding:.75rem 0 0;border-top:1px solid var(--border);margin-top:.25rem">
                    <button type="submit" class="btn-ok2">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>

    </div>

    {{-- ── Card : Supprimer le compte ───────────────────────────────────── --}}
    <div class="card" style="border-color:#fecaca">
        <div class="ch" style="background:#fef2f2">
            <div style="display:flex;align-items:center;gap:.6rem">
                <div class="ic-hico" style="background:#fee2e2;color:#dc2626">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"/>
                        <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                        <path d="M10 11v6M14 11v6"/>
                    </svg>
                </div>
                <div>
                    <span class="ct" style="color:#991b1b">Supprimer le compte</span>
                    <div class="pg-ref" style="color:#dc2626;font-size:.71rem">Cette action est irréversible.</div>
                </div>
            </div>
        </div>

        <div style="padding:1.25rem">
            <p style="font-size:.82rem;color:var(--t2);margin-bottom:1rem">
                Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées.
                Avant de supprimer votre compte, téléchargez les données que vous souhaitez conserver.
            </p>

            {{-- Bouton qui ouvre la modale --}}
            <button
                onclick="document.getElementById('mo-delete').classList.add('op')"
                class="btn-rj2">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"/>
                    <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                </svg>
                Supprimer le compte
            </button>
        </div>
    </div>

</div>

{{-- ── Modale confirmation suppression ────────────────────────────────────── --}}
<div id="mo-delete" class="mo">
    <div class="modal">
        <div class="mh">
            <div class="mh-l">
                <div class="m-ico">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                </div>
                <div>
                    <div class="m-tit">Supprimer le compte ?</div>
                    <div class="m-ref">Action définitive et irréversible</div>
                </div>
            </div>
            <button class="m-cls" onclick="document.getElementById('mo-delete').classList.remove('op')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('profile.destroy') }}">
            @csrf
            @method('DELETE')

            <div class="mb">
                <p style="font-size:.82rem;color:var(--t2)">
                    Confirmez votre mot de passe pour supprimer définitivement votre compte.
                </p>
                <div>
                    <label class="m-lbl" for="del_password">Mot de passe</label>
                    <input
                        type="password"
                        id="del_password"
                        name="password"
                        placeholder="Entrez votre mot de passe"
                        class="m-ta"
                        style="min-height:unset;resize:none;padding:.55rem .8rem"
                    >
                    @error('password', 'userDeletion')
                    <span style="font-size:.72rem;color:#dc2626;margin-top:.25rem;display:block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mf">
                <button type="button" class="btn-cancel"
                        onclick="document.getElementById('mo-delete').classList.remove('op')">
                    Annuler
                </button>
                <button type="submit" class="btn-reject">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px">
                        <polyline points="3 6 5 6 21 6"/>
                        <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                    </svg>
                    Supprimer définitivement
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Fermer la modale en cliquant dehors --}}
<script>
document.getElementById('mo-delete').addEventListener('click', function(e) {
    if (e.target === this) this.classList.remove('op');
});
</script>