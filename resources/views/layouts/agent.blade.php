<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} — Admin</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ── CSS Variables ── */
        :root {
            --bg-body:       #f0f2f8;
            --bg-sidebar:    #ffffff;
            --bg-topbar:     #ffffff;
            --bg-card:       #ffffff;
            --bg-hover:      #f1f5ff;
            --bg-active:     #eef0ff;
            --border:        #e8eaf0;
            --text-primary:  #111827;
            --text-secondary:#6b7280;
            --text-muted:    #9ca3af;
            --accent:        #4f46e5;
            --accent-light:  #eef0ff;
            --accent-text:   #4f46e5;
            --shadow:        0 1px 4px rgba(0,0,0,0.08);
            --shadow-md:     0 4px 20px rgba(0,0,0,0.08);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-body);
            color: var(--text-primary);
            min-height: 100vh;
            transition: none;
        }

        /* ── Layout shell ── */
        .shell {
            display: flex;
            min-height: 100vh;
        }

        /* ════════════════════════════════
           SIDEBAR
        ════════════════════════════════ */
        .sidebar {
            width: 240px;
            flex-shrink: 0;
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 40;
            transition: width 0.25s ease, transform 0.25s ease;
            overflow: hidden;
        }
        .sidebar.collapsed { width: 64px; }

        /* Brand */
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0 1rem;
            height: 60px;
            border-bottom: 1px solid var(--border);
            text-decoration: none;
            flex-shrink: 0;
            overflow: hidden;
            white-space: nowrap;
        }
        .brand-icon {
            width: 34px; height: 34px;
            border-radius: 9px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(79,70,229,0.35);
        }
        .brand-icon svg { width: 17px; height: 17px; color: #fff; }
        .brand-text { font-size: 0.85rem; font-weight: 800; color: var(--text-primary); letter-spacing: -0.01em; }
        .brand-badge {
            font-size: 0.55rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase;
            background: var(--accent-light); color: var(--accent-text);
            padding: 2px 6px; border-radius: 20px;
        }

        /* Nav */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 0.75rem 0;
            scrollbar-width: thin;
            scrollbar-color: var(--border) transparent;
        }

        .nav-section-label {
            font-size: 0.6rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--text-muted);
            padding: 0.75rem 1.1rem 0.3rem;
            white-space: nowrap;
            overflow: hidden;
            transition: opacity 0.2s;
        }
        .sidebar.collapsed .nav-section-label { opacity: 0; }

        /* Nav item */
        .nav-item { position: relative; }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding: 0.52rem 1rem;
            margin: 1px 0.5rem;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-decoration: none;
            cursor: pointer;
            border: none;
            background: none;
            width: calc(100% - 1rem);
            text-align: left;
            white-space: nowrap;
            overflow: hidden;
            transition: background 0.15s, color 0.15s;
        }
        .nav-link:hover { background: var(--bg-hover); color: var(--text-primary); }
        .nav-link.active { background: var(--bg-active); color: var(--accent-text); }
        .nav-link.active .nav-icon { color: var(--accent); }

        .nav-icon {
            width: 17px; height: 17px;
            flex-shrink: 0;
            transition: color 0.15s;
        }
        .nav-label { flex: 1; }
        .nav-chevron {
            width: 13px; height: 13px;
            flex-shrink: 0;
            opacity: 0.5;
            transition: transform 0.2s;
        }
        .nav-link.open .nav-chevron { transform: rotate(90deg); }

        /* Submenu */
        .nav-sub {
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.25s ease;
        }
        .nav-sub.open { max-height: 400px; }

        .nav-sub-link {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.42rem 1rem 0.42rem 2.8rem;
            margin: 1px 0.5rem;
            border-radius: 7px;
            font-size: 0.78rem;
            font-weight: 500;
            color: var(--text-secondary);
            text-decoration: none;
            white-space: nowrap;
            transition: background 0.15s, color 0.15s;
        }
        .nav-sub-link:hover { background: var(--bg-hover); color: var(--text-primary); }
        .nav-sub-link.active { color: var(--accent-text); background: var(--bg-active); }

        .sub-dot {
            width: 5px; height: 5px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .dot-blue   { background: #3b82f6; }
        .dot-red    { background: #ef4444; }
        .dot-yellow { background: #f59e0b; }
        .dot-green  { background: #10b981; }
        .dot-gray   { background: #6b7280; }
        .dot-orange { background: #f97316; }
        .dot-teal   { background: #14b8a6; }

        /* Sidebar collapsed — tooltip labels */
        .sidebar.collapsed .nav-label,
        .sidebar.collapsed .nav-chevron,
        .sidebar.collapsed .nav-sub { display: none; }
        .sidebar.collapsed .nav-link { justify-content: center; padding: 0.52rem; margin: 1px 0.4rem; width: calc(100% - 0.8rem); }

        /* Sidebar divider */
        .sidebar-divider { height: 1px; background: var(--border); margin: 0.5rem 1rem; }

        /* Sidebar footer */
        .sidebar-footer {
            padding: 0.75rem 0.5rem;
            border-top: 1px solid var(--border);
            flex-shrink: 0;
        }

        /* ════════════════════════════════
           MAIN AREA
        ════════════════════════════════ */
        .main {
            flex: 1;
            margin-left: 240px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left 0.25s ease;
        }
        .main.collapsed { margin-left: 64px; }

        /* ── Topbar ── */
        .topbar {
            height: 60px;
            background: var(--bg-topbar);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 30;
            box-shadow: var(--shadow);
        }

        .topbar-toggle {
            width: 34px; height: 34px;
            border-radius: 8px;
            background: var(--bg-hover);
            border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            color: var(--text-secondary);
            flex-shrink: 0;
            transition: all 0.15s;
        }
        .topbar-toggle:hover { color: var(--text-primary); background: var(--bg-active); }
        .topbar-toggle svg { width: 16px; height: 16px; }

        /* Breadcrumb */
        .topbar-breadcrumb {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.8rem;
            color: var(--text-muted);
            overflow: hidden;
        }
        .breadcrumb-sep { opacity: 0.4; }
        .breadcrumb-current { color: var(--text-primary); font-weight: 600; }

        /* Right actions */
        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Notif */
        .notif-btn {
            position: relative;
            width: 34px; height: 34px;
            border-radius: 8px;
            background: var(--bg-hover);
            border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.15s;
        }
        .notif-btn:hover { color: var(--text-primary); background: var(--bg-active); }
        .notif-btn svg { width: 15px; height: 15px; }
        .notif-dot {
            position: absolute; top: 7px; right: 7px;
            width: 6px; height: 6px; border-radius: 50%;
            background: #ef4444;
            border: 1.5px solid var(--bg-topbar);
        }

        /* User pill */
        .user-pill {
            display: flex; align-items: center; gap: 0.5rem;
            padding: 0.25rem 0.6rem 0.25rem 0.25rem;
            border-radius: 999px;
            background: var(--bg-hover);
            border: 1px solid var(--border);
            cursor: pointer;
            transition: all 0.15s;
            position: relative;
        }
        .user-pill:hover { background: var(--bg-active); }
        .user-avatar {
            width: 26px; height: 26px; border-radius: 50%;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.65rem; font-weight: 700; color: #fff;
        }
        .user-name { font-size: 0.78rem; font-weight: 600; color: var(--text-primary); max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .user-chevron { width: 11px; height: 11px; color: var(--text-muted); }

        /* User dropdown */
        .user-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            min-width: 180px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0.35rem;
            box-shadow: var(--shadow-md);
            display: none;
            z-index: 200;
        }
        .user-dropdown.open { display: block; }
        .user-dd-link {
            display: flex; align-items: center; gap: 0.6rem;
            padding: 0.45rem 0.65rem;
            border-radius: 6px;
            font-size: 0.8rem; font-weight: 500;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.15s;
            cursor: pointer; border: none; background: none; width: 100%; text-align: left;
        }
        .user-dd-link:hover { background: var(--bg-hover); color: var(--text-primary); }
        .user-dd-link svg { width: 13px; height: 13px; opacity: 0.6; }
        .user-dd-sep { height: 1px; background: var(--border); margin: 0.25rem 0; }

        /* ── Page content ── */
        .page-content {
            flex: 1;
            padding: 1.75rem;
        }

        /* ── Mobile overlay ── */
        .sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 39;
        }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); width: 240px !important; }
            .sidebar.mobile-open { transform: translateX(0); }
            .main { margin-left: 0 !important; }
            .sidebar-overlay.open { display: block; }
        }
    </style>
</head>
<body x-data="{
    collapsed: localStorage.getItem('sidebar') === 'collapsed',
    mobileOpen: false,
    userOpen: false,
    openMenu: null,
    toggleSidebar() {
        this.collapsed = !this.collapsed;
        localStorage.setItem('sidebar', this.collapsed ? 'collapsed' : 'open');
    },
    toggleMenu(key) { this.openMenu = this.openMenu === key ? null : key; }
}">

<div class="shell">

    {{-- ══════════════════ SIDEBAR ══════════════════ --}}
    <aside class="sidebar" :class="{ 'collapsed': collapsed, 'mobile-open': mobileOpen }">

        {{-- Brand --}}
        <a href="{{ route('agent.dashboard') }}" class="sidebar-brand">
            <div class="brand-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                    <path d="M2 17l10 5 10-5M2 12l10 5 10-5"/>
                </svg>
            </div>
            <span class="brand-text">{{ config('app.name', 'GovAdmin') }}</span>
            <span class="brand-badge">Admin</span>
        </a>

        {{-- Navigation --}}
        <nav class="sidebar-nav">

            {{-- Dashboard --}}
            <div class="nav-section-label">Principal</div>
            <div class="nav-item">
                <a href="{{ route('agent.dashboard') }}"
                   class="nav-link {{ request()->routeIs('agent.dashboard') ? 'active' : '' }}">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                        <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                        <rect x="3" y="14" width="7" height="7" rx="1.5"/>
                        <rect x="14" y="14" width="7" height="7" rx="1.5"/>
                    </svg>
                    <span class="nav-label">Dashboard</span>
                </a>
            </div>

            <div class="sidebar-divider"></div>

            {{-- Déclarations --}}
            <div class="nav-section-label">Gestion</div>

            <div class="nav-item">
                <button @click="toggleMenu('decl')"
                        class="nav-link {{ request()->routeIs('agent.declarations.*') ? 'active open' : '' }}"
                        :class="{ 'open': openMenu === 'decl' }">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                        <path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/>
                    </svg>
                    <span class="nav-label">Déclarations</span>
                    <svg class="nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </button>
                <div class="nav-sub" :class="{ 'open': openMenu === 'decl' || {{ request()->routeIs('agent.declarations.*') ? 'true' : 'false' }} }">
                    <a href="{{ route('agent.declarations.soumis') }}" class="nav-sub-link {{ request()->routeIs('agent.declarations.soumis') ? 'active' : '' }}">
                        <span class="sub-dot dot-blue"></span> Soumis
                    </a>
                    <a href="{{ route('agent.declarations.non-paye') }}" class="nav-sub-link {{ request()->routeIs('agent.declarations.non-paye') ? 'active' : '' }}">
                        <span class="sub-dot dot-red"></span> Non Payé
                    </a>
                    <a href="{{ route('agent.declarations.en-traitement') }}" class="nav-sub-link {{ request()->routeIs('agent.declarations.en-traitement') ? 'active' : '' }}">
                        <span class="sub-dot dot-yellow"></span> En Traitement
                    </a>
                    <a href="{{ route('agent.declarations.valider') }}" class="nav-sub-link {{ request()->routeIs('agent.declarations.valider') ? 'active' : '' }}">
                        <span class="sub-dot dot-green"></span> Validées
                    </a>
                    <a href="{{ route('agent.declarations.rejeter') }}" class="nav-sub-link {{ request()->routeIs('agent.declarations.rejeter') ? 'active' : '' }}">
                        <span class="sub-dot dot-gray"></span> Rejetées
                    </a>
                </div>
            </div>

            {{-- Entreprises --}}
            <div class="nav-item">
                <button @click="toggleMenu('entr')"
                        class="nav-link {{ request()->routeIs('agent.entreprises.*') || request()->routeIs('agent.gerants.*') ? 'active' : '' }}"
                        :class="{ 'open': openMenu === 'entr' }">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                        <path d="M9 22V12h6v10"/>
                    </svg>
                    <span class="nav-label">Entreprises</span>
                    <svg class="nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </button>
                <div class="nav-sub" :class="{ 'open': openMenu === 'entr' || {{ request()->routeIs('agent.entreprises.*') || request()->routeIs('agent.gerants.*') ? 'true' : 'false' }} }">
                    <a href="{{ route('agent.entreprises.index') }}" class="nav-sub-link {{ request()->routeIs('agent.entreprises.index') ? 'active' : '' }}">
                        <svg style="width:11px;height:11px;opacity:.5;flex-shrink:0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/></svg>
                        Entreprises
                    </a>
                    <a href="{{ route('agent.gerants.index') }}" class="nav-sub-link {{ request()->routeIs('agent.gerants.index') ? 'active' : '' }}">
                        <svg style="width:11px;height:11px;opacity:.5;flex-shrink:0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="7" r="4"/><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/></svg>
                        Gérants
                    </a>
                </div>
            </div>

            {{-- Attestations --}}
            <div class="nav-item">
                <button @click="toggleMenu('attest')"
                        class="nav-link {{ request()->routeIs('agent.attestations.*') ? 'active' : '' }}"
                        :class="{ 'open': openMenu === 'attest' }">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="8" r="6"/>
                        <path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/>
                    </svg>
                    <span class="nav-label">Attestations</span>
                    <svg class="nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </button>
                <div class="nav-sub" :class="{ 'open': openMenu === 'attest' || {{ request()->routeIs('agent.attestations.*') ? 'true' : 'false' }} }">
                    <a href="{{ route('agent.attestations.en-cours') }}" class="nav-sub-link {{ request()->routeIs('agent.attestations.en-cours') ? 'active' : '' }}">
                        <span class="sub-dot dot-orange"></span> En Cours
                    </a>
                    <a href="{{ route('agent.attestations.index') }}" class="nav-sub-link {{ request()->routeIs('agent.attestations.index') ? 'active' : '' }}">
                        <span class="sub-dot" style="background:#6366f1"></span> Toutes
                    </a>
                </div>
            </div>

            <div class="sidebar-divider"></div>

            {{-- Analyses --}}
            <div class="nav-section-label">Insights</div>

            <div class="nav-item">
                <button @click="toggleMenu('anal')"
                        class="nav-link {{ request()->routeIs('agent.analyses.*') ? 'active' : '' }}"
                        :class="{ 'open': openMenu === 'anal' }">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                    </svg>
                    <span class="nav-label">Analyses</span>
                    <svg class="nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </button>
                <div class="nav-sub" :class="{ 'open': openMenu === 'anal' || {{ request()->routeIs('agent.analyses.*') ? 'true' : 'false' }} }">
                    <a href="{{ route('agent.analyses.statistiques') }}" class="nav-sub-link {{ request()->routeIs('agent.analyses.statistiques') ? 'active' : '' }}">
                        <svg style="width:11px;height:11px;opacity:.5;flex-shrink:0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                        Statistiques
                    </a>
                    <a href="{{ route('agent.analyses.rapports') }}" class="nav-sub-link {{ request()->routeIs('agent.analyses.rapports') ? 'active' : '' }}">
                        <svg style="width:11px;height:11px;opacity:.5;flex-shrink:0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
                        Rapports
                    </a>
                </div>
            </div>

            <div class="sidebar-divider"></div>

            {{-- Administration --}}
            <div class="nav-section-label">Système</div>

            <div class="nav-item">
                <button @click="toggleMenu('adm')"
                        class="nav-link {{ request()->routeIs('agent.admin.*') ? 'active' : '' }}"
                        :class="{ 'open': openMenu === 'adm' }">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                    <span class="nav-label">Administration</span>
                    <svg class="nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </button>
                <div class="nav-sub" :class="{ 'open': openMenu === 'adm' || {{ request()->routeIs('agent.admin.*') ? 'true' : 'false' }} }">
                    <a href="{{ route('agent.admin.utilisateurs') }}" class="nav-sub-link {{ request()->routeIs('agent.admin.utilisateurs') ? 'active' : '' }}">
                        <svg style="width:11px;height:11px;opacity:.5;flex-shrink:0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                        Utilisateurs
                    </a>
                    <a href="{{ route('agent.admin.roles') }}" class="nav-sub-link {{ request()->routeIs('agent.admin.roles') ? 'active' : '' }}">
                        <svg style="width:11px;height:11px;opacity:.5;flex-shrink:0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                        Rôles &amp; Permissions
                    </a>
                    <a href="{{ route('agent.admin.logs') }}" class="nav-sub-link {{ request()->routeIs('agent.admin.logs') ? 'active' : '' }}">
                        <svg style="width:11px;height:11px;opacity:.5;flex-shrink:0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        Logs (Audit trail)
                    </a>
                </div>
            </div>

        </nav>

        {{-- Sidebar footer --}}
        <div class="sidebar-footer">
            <a href="{{ route('profile.edit') }}" class="nav-link">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                </svg>
                <span class="nav-label">Profil</span>
            </a>
        </div>
    </aside>

    {{-- Overlay mobile --}}
    <div class="sidebar-overlay" :class="{ 'open': mobileOpen }" @click="mobileOpen = false"></div>

    {{-- ══════════════════ MAIN ══════════════════ --}}
    <div class="main" :class="{ 'collapsed': collapsed }">

        {{-- Topbar --}}
        <header class="topbar">

            {{-- Sidebar toggle --}}
            <button @click="collapsed ? toggleSidebar() : toggleSidebar(); mobileOpen = !mobileOpen" class="topbar-toggle">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Breadcrumb --}}
            <div class="topbar-breadcrumb">
                <span>Admin</span>
                <span class="breadcrumb-sep">/</span>
                <span class="breadcrumb-current">{{ $pageTitle ?? 'Dashboard' }}</span>
            </div>

            {{-- Actions --}}
            <div class="topbar-actions">

                {{-- Notifications --}}
                <a href="#" class="notif-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/>
                    </svg>
                    <span class="notif-dot"></span>
                </a>

                {{-- User --}}
                <div class="nav-item" @click.away="userOpen = false">
                    <div @click="userOpen = !userOpen" class="user-pill">
                        <div class="user-avatar">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        <svg class="user-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M6 9l6 6 6-6"/>
                        </svg>
                    </div>
                    <div class="user-dropdown" :class="{ 'open': userOpen }">
                        <a href="{{ route('profile.edit') }}" class="user-dd-link">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            Profil
                        </a>
                        <div class="user-dd-sep"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="user-dd-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </header>

        {{-- Page content --}}
        <main class="page-content">
            {{ $slot }}
        </main>

    </div>
</div>

</body>
</html>