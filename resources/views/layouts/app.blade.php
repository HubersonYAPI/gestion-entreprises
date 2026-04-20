<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Ges_Decl') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f0f2f8; color: #111827; }

        :root {
            --sb-bg:  #1b2341;
            --sb-bdr: rgba(255,255,255,.07);
            --sb-txt: rgba(255,255,255,.50);
            --sb-hov: rgba(255,255,255,.08);
            --sb-act: #2f54eb;
            --accent: #2f54eb;
            --border: #e4e8f0;
            --white:  #ffffff;
            --bg:     #f0f2f8;
            --t1:     #111827;
            --t2:     #4b5563;
            --t3:     #9ca3af;
            --sh-sm:  0 1px 3px rgba(0,0,0,.06);
            --sh:     0 4px 16px rgba(0,0,0,.07);
        }

        /* ══ SHELL ══ */
        .shell { display: flex; min-height: 100vh; }

        /* ══ SIDEBAR ══ */
        .sb {
            width: 240px; flex-shrink: 0; background: var(--sb-bg);
            display: flex; flex-direction: column;
            position: fixed; inset: 0 auto 0 0;
            z-index: 50; transition: width .25s ease; overflow: hidden;
        }
        .sb.col { width: 60px; }

        /* Brand */
        .sb-brand {
            display: flex; align-items: center; gap: .7rem;
            padding: 0 1rem; height: 60px;
            border-bottom: 1px solid var(--sb-bdr);
            text-decoration: none; overflow: hidden; white-space: nowrap; flex-shrink: 0;
        }
        .sb-logo {
            width: 34px; height: 34px; border-radius: 9px; background: var(--accent);
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(47,84,235,.4);
        }
        .sb-logo svg { width: 17px; height: 17px; color: #fff; }
        .sb-app  { font-size: .86rem; font-weight: 800; color: #fff; }
        .sb-role {
            font-size: .58rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase;
            background: rgba(47,84,235,.35); color: #93c5fd;
            padding: 2px 7px; border-radius: 20px; flex-shrink: 0;
        }

        /* Nav */
        .sb-nav { flex: 1; overflow-y: auto; overflow-x: hidden; padding: .5rem 0; scrollbar-width: thin; scrollbar-color: rgba(255,255,255,.1) transparent; }

        .sb-sec {
            font-size: .59rem; font-weight: 700; letter-spacing: .13em; text-transform: uppercase;
            color: rgba(255,255,255,.28); padding: .85rem 1.1rem .3rem;
            white-space: nowrap; overflow: hidden; transition: opacity .2s;
        }
        .sb.col .sb-sec { opacity: 0; }

        .sb-link {
            display: flex; align-items: center; gap: .65rem;
            padding: .48rem .9rem; margin: 1px .45rem; border-radius: 7px;
            font-size: .8rem; font-weight: 500; color: var(--sb-txt);
            text-decoration: none; cursor: pointer; border: none; background: none;
            width: calc(100% - .9rem); text-align: left;
            white-space: nowrap; overflow: hidden; transition: background .15s, color .15s;
        }
        .sb-link:hover { background: var(--sb-hov); color: rgba(255,255,255,.9); }
        .sb-link.act   { background: var(--sb-act); color: #fff; }
        .sb-ico  { width: 15px; height: 15px; flex-shrink: 0; }
        .sb-lbl  { flex: 1; }
        .sb-chev { width: 12px; height: 12px; flex-shrink: 0; opacity: .45; transition: transform .2s; }
        .sb-link.op .sb-chev { transform: rotate(90deg); }

        .sb-sub { overflow: hidden; max-height: 0; transition: max-height .25s ease; }
        .sb-sub.op { max-height: 320px; }
        .sb-sl {
            display: flex; align-items: center; gap: .55rem;
            padding: .38rem .9rem .38rem 2.65rem; margin: 1px .45rem; border-radius: 6px;
            font-size: .76rem; font-weight: 400; color: rgba(255,255,255,.42);
            text-decoration: none; transition: background .15s, color .15s;
        }
        .sb-sl:hover { background: rgba(255,255,255,.06); color: rgba(255,255,255,.8); }
        .sb-sl.act   { color: #fff; background: rgba(47,84,235,.38); }
        .sdot { width: 5px; height: 5px; border-radius: 50%; flex-shrink: 0; }

        .sb.col .sb-lbl, .sb.col .sb-chev, .sb.col .sb-sub { display: none; }
        .sb.col .sb-link { justify-content: center; padding: .48rem; margin: 1px .4rem; width: calc(100% - .8rem); }

        .sb-div { height: 1px; background: var(--sb-bdr); margin: .45rem .9rem; }
        .sb-ft  { padding: .6rem .45rem; border-top: 1px solid var(--sb-bdr); flex-shrink: 0; }

        /* ══ MAIN ══ */
        .main { flex: 1; margin-left: 240px; display: flex; flex-direction: column; min-height: 100vh; transition: margin-left .25s ease; min-width: 0; overflow-x: hidden; max-width: calc(100vw - 240px); }
        .main.col { margin-left: 60px; max-width: calc(100vw - 60px); }

        /* Topbar */
        .topbar {
            height: 60px; background: var(--white); border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 1rem; padding: 0 1.5rem;
            position: sticky; top: 0; z-index: 30; box-shadow: var(--sh-sm); flex-shrink: 0;
        }
        .tb-tog {
            width: 32px; height: 32px; border-radius: 7px;
            background: var(--bg); border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: var(--t2); flex-shrink: 0; transition: all .15s;
        }
        .tb-tog:hover { background: #e8edfa; color: var(--accent); }
        .tb-tog svg { width: 16px; height: 16px; }

        .tb-crumb { flex: 1; display: flex; align-items: center; gap: .4rem; font-size: .78rem; color: var(--t3); }
        .tb-cur { color: var(--t1); font-weight: 600; }
        .tb-sep { opacity: .4; }
        .tb-r   { display: flex; align-items: center; gap: .5rem; }

        .tb-ic {
            position: relative; width: 32px; height: 32px; border-radius: 7px;
            background: var(--bg); border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: var(--t2); text-decoration: none; transition: all .15s;
        }
        .tb-ic:hover { background: #e8edfa; color: var(--accent); }
        .tb-ic svg { width: 15px; height: 15px; }
        .tb-ndot { position: absolute; top: 6px; right: 6px; width: 6px; height: 6px; border-radius: 50%; background: #ef4444; border: 1.5px solid #fff; }

        .tb-user {
            display: flex; align-items: center; gap: .5rem;
            padding: .25rem .6rem .25rem .25rem; border-radius: 999px;
            background: var(--bg); border: 1px solid var(--border);
            cursor: pointer; transition: all .15s; position: relative;
        }
        .tb-user:hover { background: #e8edfa; border-color: #c7d0f5; }
        .tb-av {
            width: 26px; height: 26px; border-radius: 50%;
            background: linear-gradient(135deg,#2f54eb,#7c3aed);
            display: flex; align-items: center; justify-content: center;
            font-size: .64rem; font-weight: 700; color: #fff;
        }
        .tb-un { font-size: .78rem; font-weight: 600; color: var(--t1); max-width: 110px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .tb-uc { width: 11px; height: 11px; color: var(--t3); }

        .tb-drop {
            position: absolute; top: calc(100% + 8px); right: 0;
            min-width: 175px; background: var(--white); border: 1px solid var(--border);
            border-radius: 10px; padding: .35rem; box-shadow: 0 12px 40px rgba(0,0,0,.12);
            display: none; z-index: 200;
        }
        .tb-drop.op { display: block; }
        .tb-dl {
            display: flex; align-items: center; gap: .55rem;
            padding: .44rem .65rem; border-radius: 6px;
            font-size: .79rem; font-weight: 500; color: var(--t2);
            text-decoration: none; transition: all .15s;
            cursor: pointer; border: none; background: none; width: 100%; text-align: left;
        }
        .tb-dl:hover { background: var(--bg); color: var(--t1); }
        .tb-dl svg { width: 13px; height: 13px; opacity: .55; }
        .tb-ds { height: 1px; background: var(--border); margin: .25rem 0; }

        /* Page content */
        .page-content { flex: 1; padding: 1.75rem; min-width: 0; overflow-x: hidden; width: 100%; box-sizing: border-box; }

        /* Mobile */
        .sb-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.4); z-index: 49; }
        @media (max-width: 768px) {
            .sb    { transform: translateX(-100%); width: 240px !important; }
            .sb.mob { transform: translateX(0); }
            .main  { margin-left: 0 !important; }
            .sb-overlay.op { display: block; }
        }
    </style>
</head>
<body x-data="{
    col: localStorage.getItem('sb') === '1',
    mob: false, uop: false, menu: null,
    tog() { this.col = !this.col; localStorage.setItem('sb', this.col ? '1':'0'); },
    tm(k) { this.menu = this.menu === k ? null : k; }
}">

<div class="shell">

    {{-- ════════════════ SIDEBAR ════════════════ --}}
    <aside class="sb" :class="{ 'col': col, 'mob': mob }">

        {{-- Brand --}}
        <a href="{{ route('dashboard') }}" class="sb-brand">
            <div class="sb-logo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                    <path d="M9 22V12h6v10"/>
                </svg>
            </div>
            <span class="sb-app">{{ config('app.name','Ges_Decl') }}</span>
            {{-- Badge rôle --}}
            @auth
                @if(Auth::user()->hasRole('AGENT') || Auth::user()->hasRole('CONTROLEUR') || Auth::user()->hasRole('SUPER_ADMIN'))
                    <span class="sb-role">Admin</span>
                @else
                    <span class="sb-role">Gérant</span>
                @endif
            @endauth
        </a>

        <nav class="sb-nav">

            {{-- ══ NAVIGATION GÉRANT ══ --}}
            @auth
            @if(!Auth::user()->hasRole('AGENT') && !Auth::user()->hasRole('CONTROLEUR') && !Auth::user()->hasRole('SUPER_ADMIN'))

                <div class="sb-sec">Principal</div>
                <div>
                    <a href="{{ route('dashboard') }}"
                       class="sb-link {{ request()->routeIs('dashboard') ? 'act' : '' }}">
                        <svg class="sb-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                            <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                            <rect x="3" y="14" width="7" height="7" rx="1.5"/>
                            <rect x="14" y="14" width="7" height="7" rx="1.5"/>
                        </svg>
                        <span class="sb-lbl">Dashboard</span>
                    </a>
                </div>

                <div class="sb-div"></div>
                <div class="sb-sec">Gestion</div>

                {{-- Déclarations --}}
                <div>
                    <button @click="tm('d')"
                        class="sb-link {{ request()->routeIs('declarations.*') ? 'act' : '' }}"
                        :class="{'op': menu==='d'}">
                        <svg class="sb-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                            <path d="M14 2v6h6M16 13H8M16 17H8"/>
                        </svg>
                        <span class="sb-lbl">Déclarations</span>
                        <svg class="sb-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                    </button>
                    <div class="sb-sub" :class="{'op': menu==='d' || {{ request()->routeIs('declarations.*') ? 'true':'false' }} }">
                        <a href="{{ route('declarations.index') }}"
                           class="sb-sl {{ request()->routeIs('declarations.index') && !request('statut') ? 'act':'' }}">
                            <span class="sdot" style="background:#6b7280"></span>Toutes
                        </a>
                        <a href="{{ route('declarations.index', ['statut'=>'brouillon']) }}"
                           class="sb-sl {{ request('statut')==='brouillon' ? 'act':'' }}">
                            <span class="sdot" style="background:#6366f1"></span>Brouillons
                        </a>
                        <a href="{{ route('declarations.index', ['statut'=>'soumis']) }}"
                           class="sb-sl {{ request('statut')==='soumis' ? 'act':'' }}">
                            <span class="sdot" style="background:#3b82f6"></span>Soumises
                        </a>
                        <a href="{{ route('declarations.index', ['statut'=>'approuve']) }}"
                           class="sb-sl {{ request('statut')==='approuve' ? 'act':'' }}">
                            <span class="sdot" style="background:#10b981"></span>En attente paiement
                        </a>
                        <a href="{{ route('declarations.index', ['statut'=>'paye']) }}"
                           class="sb-sl {{ request('statut')==='paye' ? 'act':'' }}">
                            <span class="sdot" style="background:#14b8a6"></span>Soldées
                        </a>
                        <a href="{{ route('declarations.index', ['statut'=>'en_traitement']) }}"
                           class="sb-sl {{ request('statut')==='en_traitement' ? 'act':'' }}">
                            <span class="sdot" style="background:#f59e0b"></span>En traitement
                        </a>
                        <a href="{{ route('declarations.index', ['statut'=>'valide']) }}"
                           class="sb-sl {{ request('statut')==='valide' ? 'act':'' }}">
                            <span class="sdot" style="background:#a855f7"></span>Validées
                        </a>
                        <a href="{{ route('declarations.index', ['statut'=>'rejete']) }}"
                           class="sb-sl {{ request('statut')==='rejete' ? 'act':'' }}">
                            <span class="sdot" style="background:#ef4444"></span>Rejetées
                        </a>
                    </div>
                </div>

                {{-- Entreprises --}}
                <div>
                    <button @click="tm('e')"
                        class="sb-link {{ request()->routeIs('entreprises.*') || request()->routeIs('gerant.*') ? 'act':'' }}"
                        :class="{'op': menu==='e'}">
                        <svg class="sb-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="7" width="20" height="14" rx="2"/>
                            <path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
                        </svg>
                        <span class="sb-lbl">Entreprises</span>
                        <svg class="sb-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                    </button>
                    <div class="sb-sub" :class="{'op': menu==='e' || {{ request()->routeIs('entreprises.*') || request()->routeIs('gerant.*') ? 'true':'false' }} }">
                        <a href="{{ route('entreprises.index') }}"
                           class="sb-sl {{ request()->routeIs('entreprises.*') ? 'act':'' }}">
                            <svg style="width:11px;height:11px;opacity:.5;flex-shrink:0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/></svg>
                            Entreprise
                        </a>
                        <a href="{{ route('gerant.show') }}"
                           class="sb-sl {{ request()->routeIs('gerant.show') || request()->routeIs('gerant.edit') ? 'act':'' }}">
                            <svg style="width:11px;height:11px;opacity:.5;flex-shrink:0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="7" r="4"/><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/></svg>
                            Gérant
                        </a>
                    </div>
                </div>

                {{-- Attestations --}}
                <div> 
                    <a href="{{ route('attestations.index') }}"
                    class="sb-link {{ request()->routeIs('attestations.*') ? 'act':'' }}">
                        <svg class="sb-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="8" r="6"/>
                            <path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/>
                        </svg>
                        <span class="sb-lbl">Attestations</span>
                    </a>
                </div>

            @endif
            @endauth

        </nav>

        {{-- Profil (footer sidebar) --}}
        <div class="sb-ft">
            <a href="{{ route('gerant.show') }}"
               class="sb-link {{ request()->routeIs('gerant.*') ? 'act':'' }}">
                <svg class="sb-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                <span class="sb-lbl">Profil</span>
            </a>
        </div>
    </aside>

    <div class="sb-overlay" :class="{'op': mob}" @click="mob=false"></div>

    {{-- ════════════════ MAIN ════════════════ --}}
    <div class="main" :class="{'col': col}">

        {{-- Topbar --}}
        <header class="topbar">
            <button @click="tog()" class="tb-tog">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <div class="tb-crumb">
                <span>Espace Gérant</span>
                <span class="tb-sep">/</span>
                <span class="tb-cur">
                    @if(request()->routeIs('dashboard'))          Dashboard
                    @elseif(request()->routeIs('declarations.create')) Nouvelle déclaration
                    @elseif(request()->routeIs('declarations.edit'))   Modifier la déclaration
                    @elseif(request()->routeIs('declarations.show'))   Détail déclaration
                    @elseif(request()->routeIs('declarations.*'))  Déclarations
                    @elseif(request()->routeIs('entreprises.create')) Nouvelle entreprise
                    @elseif(request()->routeIs('entreprises.edit'))   Modifier l'entreprise
                    @elseif(request()->routeIs('entreprises.*'))  Entreprises
                    @elseif(request()->routeIs('gerant.edit'))    Modifier le profil
                    @elseif(request()->routeIs('gerant.*'))       Profil Gérant
                    @elseif(request()->routeIs('documents.*'))    Documents
                    @else Page
                    @endif
                </span>
            </div>

            <div class="tb-r">
                {{-- <a href="#" class="tb-ic">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/>
                    </svg>
                    <span class="tb-ndot"></span>
                </a> --}}
                <x-notification-bell />

                <div style="position:relative" @click.away="uop=false">
                    <div @click="uop=!uop" class="tb-user">
                        <div class="tb-av">{{ strtoupper(substr(Auth::user()->name,0,2)) }}</div>
                        <span class="tb-un">{{ Auth::user()->name }}</span>
                        <svg class="tb-uc" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M6 9l6 6 6-6"/>
                        </svg>
                    </div>
                    <div class="tb-drop" :class="{'op': uop}">
                        <a href="{{ route('gerant.show') }}" class="tb-dl">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            Mon profil
                        </a>
                        <a href="{{ route('profile.edit') }}" class="tb-dl">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 010 14.14M4.93 4.93a10 10 0 000 14.14"/></svg>
                            Paramètres
                        </a>
                        <div class="tb-ds"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="tb-dl">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- ██ SLOT — contenu injecté par chaque vue ██ --}}
        <main class="page-content">
            {{ $slot }}
        </main>

    </div>
</div>
</body>
</html>