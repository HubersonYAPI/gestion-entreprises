<nav x-data="{
    open: false,
    activeMenu: null,
    toggleMenu(menu) {
        this.activeMenu = this.activeMenu === menu ? null : menu;
    }
}" class="admin-nav">

    <style>
        @import url('https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700&display=swap');

        .admin-nav {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #0f1117;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .admin-nav * { box-sizing: border-box; }

        /* Top bar */
        .nav-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1.5rem;
            height: 56px;
            gap: 2rem;
        }

        /* Logo zone */
        .nav-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            flex-shrink: 0;
        }
        .nav-brand-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .nav-brand-icon svg { width: 18px; height: 18px; color: #fff; }
        .nav-brand-label {
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #e2e8f0;
        }
        .nav-brand-badge {
            font-size: 0.6rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #fff;
            padding: 2px 7px;
            border-radius: 20px;
        }

        /* Primary links */
        .nav-links {
            display: flex;
            align-items: stretch;
            gap: 0;
            flex: 1;
            height: 56px;
        }

        .nav-item {
            position: relative;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0 0.85rem;
            height: 56px;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: #94a3b8;
            text-decoration: none;
            white-space: nowrap;
            cursor: pointer;
            border: none;
            background: none;
            transition: color 0.2s;
            border-bottom: 2px solid transparent;
            user-select: none;
        }
        .nav-link:hover,
        .nav-link.active {
            color: #e2e8f0;
            border-bottom-color: #6366f1;
        }
        .nav-link svg.chevron {
            width: 12px; height: 12px;
            transition: transform 0.2s;
            opacity: 0.6;
        }
        .nav-link.dropdown-open svg.chevron {
            transform: rotate(180deg);
        }
        .nav-link svg.icon {
            width: 14px; height: 14px;
            opacity: 0.7;
        }

        /* Dropdown */
        .nav-dropdown {
            position: absolute;
            top: calc(100% + 1px);
            left: 0;
            min-width: 220px;
            background: #1a1d27;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 10px;
            padding: 0.4rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5), 0 0 0 1px rgba(99,102,241,0.1);
            display: none;
            z-index: 100;
        }
        .nav-dropdown.open { display: block; }

        .dropdown-section {
            padding: 0.3rem 0;
        }
        .dropdown-section + .dropdown-section {
            border-top: 1px solid rgba(255,255,255,0.06);
            margin-top: 0.3rem;
            padding-top: 0.6rem;
        }
        .dropdown-label {
            font-size: 0.6rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #475569;
            padding: 0.2rem 0.7rem 0.4rem;
        }

        .dropdown-link {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.5rem 0.7rem;
            border-radius: 6px;
            font-size: 0.82rem;
            font-weight: 500;
            color: #94a3b8;
            text-decoration: none;
            transition: background 0.15s, color 0.15s;
        }
        .dropdown-link:hover {
            background: rgba(99,102,241,0.1);
            color: #e2e8f0;
        }
        .dropdown-link.active {
            background: rgba(99,102,241,0.15);
            color: #a5b4fc;
        }
        .dropdown-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .dot-yellow  { background: #f59e0b; }
        .dot-red     { background: #ef4444; }
        .dot-blue    { background: #3b82f6; }
        .dot-green   { background: #10b981; }
        .dot-gray    { background: #6b7280; }
        .dot-orange  { background: #f97316; }
        .dot-purple  { background: #a855f7; }
        .dot-indigo  { background: #6366f1; }
        .dot-teal    { background: #14b8a6; }
        .dot-pink    { background: #ec4899; }

        /* Right side */
        .nav-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-shrink: 0;
        }

        .nav-notif {
            position: relative;
            width: 34px; height: 34px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 8px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.07);
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }
        .nav-notif:hover { background: rgba(255,255,255,0.08); color: #94a3b8; }
        .nav-notif svg { width: 16px; height: 16px; }
        .notif-badge {
            position: absolute;
            top: 6px; right: 6px;
            width: 7px; height: 7px;
            border-radius: 50%;
            background: #ef4444;
            border: 1.5px solid #0f1117;
        }

        .nav-user {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            cursor: pointer;
            padding: 0.3rem 0.6rem;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.07);
            background: rgba(255,255,255,0.03);
            transition: all 0.2s;
        }
        .nav-user:hover { background: rgba(255,255,255,0.07); }
        .nav-avatar {
            width: 28px; height: 28px;
            border-radius: 7px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.7rem;
            font-weight: 700;
            color: #fff;
        }
        .nav-user-name {
            font-size: 0.8rem;
            font-weight: 600;
            color: #cbd5e1;
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .nav-user svg { width: 12px; height: 12px; color: #475569; }

        /* User dropdown */
        .user-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            min-width: 180px;
            background: #1a1d27;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 10px;
            padding: 0.4rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
            display: none;
            z-index: 100;
        }
        .user-dropdown.open { display: block; }

        /* Mobile hamburger */
        .nav-hamburger {
            display: none;
            align-items: center;
            justify-content: center;
            width: 36px; height: 36px;
            border-radius: 8px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.07);
            color: #64748b;
            cursor: pointer;
        }
        .nav-hamburger svg { width: 18px; height: 18px; }

        /* Mobile drawer */
        .nav-mobile {
            display: none;
            background: #0f1117;
            border-top: 1px solid rgba(255,255,255,0.06);
        }
        .nav-mobile.open { display: block; }
        .mobile-inner { padding: 0.75rem 1rem; max-height: 80vh; overflow-y: auto; }

        .mobile-link {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.6rem 0.75rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #94a3b8;
            text-decoration: none;
            transition: all 0.15s;
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }
        .mobile-link:hover { background: rgba(255,255,255,0.05); color: #e2e8f0; }
        .mobile-link.active { color: #a5b4fc; background: rgba(99,102,241,0.1); }
        .mobile-link svg { width: 15px; height: 15px; }

        .mobile-sub {
            padding-left: 2.25rem;
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.3s ease;
        }
        .mobile-sub.open { max-height: 500px; }
        .mobile-sub-link {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.45rem 0.5rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 500;
            color: #64748b;
            text-decoration: none;
            transition: all 0.15s;
        }
        .mobile-sub-link:hover { color: #94a3b8; }
        .mobile-divider { height: 1px; background: rgba(255,255,255,0.05); margin: 0.4rem 0; }

        @media (max-width: 1024px) {
            .nav-links { display: none; }
            .nav-hamburger { display: flex; }
        }
        @media (max-width: 640px) {
            .nav-brand-label { display: none; }
        }
    </style>

    <!-- Top bar -->
    <div class="nav-topbar">

        {{-- Brand --}}
        <a href="{{ route('agent.dashboard') }}" class="nav-brand">
            <div class="nav-brand-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                    <path d="M2 17l10 5 10-5M2 12l10 5 10-5"/>
                </svg>
            </div>
            <span class="nav-brand-label">{{ config('app.name', 'AdminPanel') }}</span>
            <span class="nav-brand-badge">Admin</span>
        </a>

        {{-- Desktop links --}}
        <div class="nav-links">

            {{-- Dashboard --}}
            <div class="nav-item">
                <a href="{{ route('agent.dashboard') }}"
                   class="nav-link {{ request()->routeIs('agent.dashboard') ? 'active' : '' }}">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                        <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
                    </svg>
                    Dashboard
                </a>
            </div>

            {{-- Déclarations --}}
            <div class="nav-item" @click.away="activeMenu = activeMenu === 'declarations' ? null : activeMenu">
                <button @click="toggleMenu('declarations')"
                        :class="{ 'active': {{ request()->routeIs('agent.declarations.*') ? 'true' : 'false' }}, 'dropdown-open': activeMenu === 'declarations' }"
                        class="nav-link {{ request()->routeIs('agent.declarations.*') ? 'active' : '' }}">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                        <path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/>
                    </svg>
                    Déclarations
                    <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M6 9l6 6 6-6"/>
                    </svg>
                </button>
                <div class="nav-dropdown" :class="{ 'open': activeMenu === 'declarations' }">
                    <div class="dropdown-label">Par statut</div>
                    <a href="{{ route('agent.dashboard') }}" class="dropdown-link {{ request()->routeIs('agent.dashboard') ? 'active' : '' }}">
                        <span class="dropdown-dot dot-gray"></span> Toutes
                    </a>
                    <a href="{{ route('agent.declarations.soumis') }}" class="dropdown-link {{ request()->routeIs('agent.declarations.soumis') ? 'active' : '' }}">
                        <span class="dropdown-dot dot-blue"></span> Soumises
                    </a>
                    <a href="{{ route('agent.declarations.approuver') }}" class="dropdown-link {{ request()->routeIs('agent.declarations.approuver') ? 'active' : '' }}">
                        <span class="dropdown-dot dot-green"></span> Approuvées
                    </a>
                    <a href="{{ route('agent.declarations.payer') }}" class="dropdown-link {{ request()->routeIs('agent.declarations.payer') ? 'active' : '' }}">
                        <span class="dropdown-dot dot-teal"></span> Soldées
                    </a>
                    <a href="{{ route('agent.declarations.en-traitement') }}" class="dropdown-link {{ request()->routeIs('agent.declarations.en-traitement') ? 'active' : '' }}">
                        <span class="dropdown-dot dot-yellow"></span> En Traitement
                    </a>
                    <a href="{{ route('agent.declarations.valider') }}" class="dropdown-link {{ request()->routeIs('agent.declarations.valider') ? 'active' : '' }}">
                        <span class="dropdown-dot dot-purple"></span> Validées
                    </a>
                    <a href="{{ route('agent.declarations.rejeter') }}" class="dropdown-link {{ request()->routeIs('agent.declarations.rejeter') ? 'active' : '' }}">
                        <span class="dropdown-dot dot-red"></span> Rejetées
                    </a>
                </div>
            </div>

            {{-- Entreprises --}}
            <div class="nav-item" @click.away="activeMenu = activeMenu === 'entreprises' ? null : activeMenu">
                <button @click="toggleMenu('entreprises')"
                        :class="{ 'active': {{ request()->routeIs('agent.entreprises.*') ? 'true' : 'false' }}, 'dropdown-open': activeMenu === 'entreprises' }"
                        class="nav-link {{ request()->routeIs('agent.entreprises.*') ? 'active' : '' }}">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                        <path d="M9 22V12h6v10"/>
                    </svg>
                    Entreprises
                    <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M6 9l6 6 6-6"/>
                    </svg>
                </button>
                <div class="nav-dropdown" :class="{ 'open': activeMenu === 'entreprises' }">
                    <a href="{{ route('agent.entreprises') }}" class="dropdown-link {{ request()->routeIs('agent.entreprises') ? 'active' : '' }}">
                        <svg style="width:14px;height:14px;opacity:.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
                        </svg>
                        Entreprises
                    </a>
                    <a href="{{ route('agent.gerants') }}" class="dropdown-link {{ request()->routeIs('agent.gerants.*') ? 'active' : '' }}">
                        <svg style="width:14px;height:14px;opacity:.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                        Gérants
                    </a>
                </div>
            </div>

            {{-- Attestations --}}
            <div> 
                <a href="{{ route('agent.attestations') }}"
                class="sb-link {{ request()->routeIs('agent.attestations.*') ? 'act':'' }}">
                    <svg class="sb-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="8" r="6"/>
                        <path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/>
                    </svg>
                    <span class="sb-lbl">Attestations</span>
                </a>
            </div>

            {{-- Analyses --}}
            <div class="nav-item" @click.away="activeMenu = activeMenu === 'analyses' ? null : activeMenu">
                <button @click="toggleMenu('analyses')"
                        :class="{ 'active': {{ request()->routeIs('agent.analyses.*') ? 'true' : 'false' }}, 'dropdown-open': activeMenu === 'analyses' }"
                        class="nav-link {{ request()->routeIs('agent.analyses.*') ? 'active' : '' }}">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                    </svg>
                    Analyses
                    <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M6 9l6 6 6-6"/>
                    </svg>
                </button>
                <div class="nav-dropdown" :class="{ 'open': activeMenu === 'analyses' }">
                    <a href="{{ route('agent.analyses.statistiques') }}" class="dropdown-link {{ request()->routeIs('agent.analyses.statistiques') ? 'active' : '' }}">
                        <svg style="width:14px;height:14px;opacity:.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
                        </svg>
                        Statistiques
                    </a>
                    <a href="{{ route('agent.analyses.rapports') }}" class="dropdown-link {{ request()->routeIs('agent.analyses.rapports') ? 'active' : '' }}">
                        <svg style="width:14px;height:14px;opacity:.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8"/>
                        </svg>
                        Rapports
                    </a>
                </div>
            </div>

            {{-- Administration --}}
            <div class="nav-item" @click.away="activeMenu = activeMenu === 'admin' ? null : activeMenu">
                <button @click="toggleMenu('admin')"
                        :class="{ 'active': {{ request()->routeIs('agent.admin.*') ? 'true' : 'false' }}, 'dropdown-open': activeMenu === 'admin' }"
                        class="nav-link {{ request()->routeIs('agent.admin.*') ? 'active' : '' }}">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="3"/>
                        <path d="M19.07 4.93a10 10 0 010 14.14M4.93 4.93a10 10 0 000 14.14"/>
                    </svg>
                    Administration
                    <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M6 9l6 6 6-6"/>
                    </svg>
                </button>
                <div class="nav-dropdown" :class="{ 'open': activeMenu === 'admin' }">
                    <a href="{{ route('agent.admin.utilisateurs') }}" class="dropdown-link {{ request()->routeIs('agent.admin.utilisateurs') ? 'active' : '' }}">
                        <svg style="width:14px;height:14px;opacity:.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                        </svg>
                        Utilisateurs
                    </a>
                    <a href="{{ route('agent.admin.roles') }}" class="dropdown-link {{ request()->routeIs('agent.admin.roles') ? 'active' : '' }}">
                        <svg style="width:14px;height:14px;opacity:.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
                        </svg>
                        Rôles &amp; Permissions
                    </a>
                    <a href="{{ route('agent.admin.logs') }}" class="dropdown-link {{ request()->routeIs('agent.admin.logs') ? 'active' : '' }}">
                        <svg style="width:14px;height:14px;opacity:.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                        Logs (Audit trail)
                    </a>
                </div>
            </div>

        </div>

        {{-- Right side --}}
        <div class="nav-right">

            {{-- Notification bell --}}
            <a href="#" class="nav-notif">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/>
                </svg>
                <span class="notif-badge"></span>
            </a>

            {{-- User menu --}}
            <div class="nav-item" @click.away="activeMenu = activeMenu === 'user' ? null : activeMenu">
                <div @click="toggleMenu('user')" class="nav-user">
                    <div class="nav-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <span class="nav-user-name">{{ Auth::user()->name }}</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M6 9l6 6 6-6"/>
                    </svg>
                </div>
                <div class="user-dropdown" :class="{ 'open': activeMenu === 'user' }">
                    <a href="{{ route('profile.edit') }}" class="dropdown-link">
                        <svg style="width:14px;height:14px;opacity:.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                        {{ __('Profil') }}
                    </a>
                    <div style="height:1px;background:rgba(255,255,255,0.06);margin:0.3rem 0;"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-link" style="width:100%;border:none;cursor:pointer;">
                            <svg style="width:14px;height:14px;opacity:.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/>
                            </svg>
                            {{ __('Déconnexion') }}
                        </button>
                    </form>
                </div>
            </div>

            {{-- Hamburger --}}
            <button @click="open = !open" class="nav-hamburger">
                <svg x-show="!open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </button>

        </div>
    </div>

    {{-- Mobile drawer --}}
    <div class="nav-mobile" :class="{ 'open': open }">
        <div class="mobile-inner">

            <a href="{{ route('agent.dashboard') }}" class="mobile-link {{ request()->routeIs('agent.dashboard') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                    <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
                </svg>
                Dashboard
            </a>

            <div class="mobile-divider"></div>

            {{-- Mobile Déclarations --}}
            <button @click="toggleMenu('m-decl')" class="mobile-link" style="justify-content:space-between">
                <span style="display:flex;align-items:center;gap:.6rem">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
                    Déclarations
                </span>
                <svg style="width:12px;height:12px;transition:transform .2s" :style="activeMenu === 'm-decl' ? 'transform:rotate(180deg)' : ''" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
            </button>
            <div class="mobile-sub" :class="{ 'open': activeMenu === 'm-decl' }">
                <a href="{{ route('agent.declarations.soumis') }}" class="mobile-sub-link"><span class="dropdown-dot dot-blue"></span> Soumis</a>
                <a href="{{ route('agent.declarations.approuver') }}" class="mobile-sub-link"><span class="dropdown-dot dot-green"></span> Approuvées</a>
                <a href="{{ route('agent.declarations.payer') }}" class="mobile-sub-link"><span class="dropdown-dot dot-teal"></span> Soldées</a>
                <a href="{{ route('agent.declarations.en-traitement') }}" class="mobile-sub-link"><span class="dropdown-dot dot-yellow"></span> En Traitement</a>
                <a href="{{ route('agent.declarations.valider') }}" class="mobile-sub-link"><span class="dropdown-dot dot-purple"></span> Validées</a>
                <a href="{{ route('agent.declarations.rejeter') }}" class="mobile-sub-link"><span class="dropdown-dot dot-red"></span> Rejetées</a>
            </div>

            {{-- Mobile Entreprises --}}
            <button @click="toggleMenu('m-entr')" class="mobile-link" style="justify-content:space-between">
                <span style="display:flex;align-items:center;gap:.6rem">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    Entreprises
                </span>
                <svg style="width:12px;height:12px;transition:transform .2s" :style="activeMenu === 'm-entr' ? 'transform:rotate(180deg)' : ''" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
            </button>
            <div class="mobile-sub" :class="{ 'open': activeMenu === 'm-entr' }">
                <a href="{{ route('agent.entreprises') }}" class="mobile-sub-link">Entreprises</a>
                <a href="{{ route('agent.gerants') }}" class="mobile-sub-link">Gérants</a>
            </div>

            {{-- Mobile Attestations --}}
            <a href="{{ route('agent.attestations') }}" class="mobile-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg>
                Attestations
            </a>

            {{-- Mobile Analyses --}}
            <button @click="toggleMenu('m-anal')" class="mobile-link" style="justify-content:space-between">
                <span style="display:flex;align-items:center;gap:.6rem">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    Analyses
                </span>
                <svg style="width:12px;height:12px;transition:transform .2s" :style="activeMenu === 'm-anal' ? 'transform:rotate(180deg)' : ''" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
            </button>
            <div class="mobile-sub" :class="{ 'open': activeMenu === 'm-anal' }">
                <a href="{{ route('agent.analyses.statistiques') }}" class="mobile-sub-link">Statistiques</a>
                <a href="{{ route('agent.analyses.rapports') }}" class="mobile-sub-link">Rapports</a>
            </div>

            {{-- Mobile Administration --}}
            <button @click="toggleMenu('m-adm')" class="mobile-link" style="justify-content:space-between">
                <span style="display:flex;align-items:center;gap:.6rem">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 010 14.14M4.93 4.93a10 10 0 000 14.14"/></svg>
                    Administration
                </span>
                <svg style="width:12px;height:12px;transition:transform .2s" :style="activeMenu === 'm-adm' ? 'transform:rotate(180deg)' : ''" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
            </button>
            <div class="mobile-sub" :class="{ 'open': activeMenu === 'm-adm' }">
                <a href="{{ route('agent.admin.utilisateurs') }}" class="mobile-sub-link">Utilisateurs</a>
                <a href="{{ route('agent.admin.roles') }}" class="mobile-sub-link">Rôles &amp; Permissions</a>
                <a href="{{ route('agent.admin.logs') }}" class="mobile-sub-link">Logs (Audit trail)</a>
            </div>

            <div class="mobile-divider"></div>

            <a href="{{ route('profile.edit') }}" class="mobile-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                </svg>
                {{ Auth::user()->name }}
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="mobile-link" style="width:100%;border:none;cursor:pointer">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/>
                    </svg>
                    Déconnexion
                </button>
            </form>

        </div>
    </div>

</nav>
