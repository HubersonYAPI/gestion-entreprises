{{--
    resources/views/components/notification-bell.blade.php

    Cloche de notifications — s'utilise dans les deux layouts :
    <x-notification-bell />

    Affiche le nombre de non-lues + dropdown des 5 dernières.
--}}

@php
    $unread      = Auth::user()->unreadNotifications->take(5);
    $unreadCount = Auth::user()->unreadNotifications->count();
@endphp

<div style="position:relative" x-data="{ open: false }" @click.away="open = false">

    {{-- ── Bouton cloche ── --}}
    <button @click="open = !open"
            style="position:relative;width:32px;height:32px;border-radius:7px;background:var(--bg, #f0f2f8);border:1px solid var(--border, #e4e8f0);display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--t2, #4b5563);transition:all .15s;"
            title="Notifications">
        <svg style="width:15px;height:15px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/>
            <path d="M13.73 21a2 2 0 01-3.46 0"/>
        </svg>
        {{-- Badge nombre --}}
        @if($unreadCount > 0)
        <span style="position:absolute;top:-4px;right:-4px;min-width:16px;height:16px;border-radius:99px;background:#ef4444;color:#fff;font-size:.6rem;font-weight:800;display:flex;align-items:center;justify-content:center;padding:0 3px;border:1.5px solid #fff;line-height:1;">
            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
        </span>
        @endif
    </button>

    {{-- ── Dropdown ── --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         style="display:none;position:absolute;top:calc(100% + 8px);right:0;width:340px;background:#fff;border:1px solid #e4e8f0;border-radius:12px;box-shadow:0 16px 48px rgba(0,0,0,.14);z-index:300;overflow:hidden">

        {{-- En-tête --}}
        <div style="display:flex;align-items:center;justify-content:space-between;padding:.8rem 1rem;border-bottom:1px solid #e4e8f0;">
            <div style="font-size:.82rem;font-weight:700;color:#111827">
                Notifications
                @if($unreadCount > 0)
                <span style="font-size:.68rem;font-weight:700;background:#eff6ff;color:#2563eb;padding:1px 6px;border-radius:20px;margin-left:.3rem">
                    {{ $unreadCount }} non lue{{ $unreadCount > 1 ? 's' : '' }}
                </span>
                @endif
            </div>
            @if($unreadCount > 0)
            <form method="POST" action="{{ route('notifications.markAllAsRead') }}">
                @csrf
                <button type="submit" style="font-size:.71rem;font-weight:600;color:#2563eb;background:none;border:none;cursor:pointer;padding:0;">
                    Tout marquer lu
                </button>
            </form>
            @endif
        </div>

        {{-- Liste des notifications --}}
        <div style="max-height:360px;overflow-y:auto">
            @forelse($unread as $notif)
            @php
                $data   = $notif->data;
                $couleur = match($data['couleur'] ?? 'gray') {
                    'green'  => ['bg'=>'#ecfdf5','dot'=>'#059669','txt'=>'#065f46'],
                    'red'    => ['bg'=>'#fef2f2','dot'=>'#dc2626','txt'=>'#991b1b'],
                    'blue'   => ['bg'=>'#eff6ff','dot'=>'#2563eb','txt'=>'#1d4ed8'],
                    'yellow' => ['bg'=>'#fefce8','dot'=>'#ca8a04','txt'=>'#92400e'],
                    'orange' => ['bg'=>'#fff7ed','dot'=>'#ea580c','txt'=>'#9a3412'],
                    'teal'   => ['bg'=>'#f0fdfa','dot'=>'#0d9488','txt'=>'#0f766e'],
                    'purple' => ['bg'=>'#faf5ff','dot'=>'#9333ea','txt'=>'#6b21a8'],
                    default  => ['bg'=>'#f8fafc','dot'=>'#64748b','txt'=>'#475569'],
                };
            @endphp
            <a href="{{ route('notifications.markAsRead', $notif->id) }}"
               style="display:flex;align-items:flex-start;gap:.75rem;padding:.8rem 1rem;text-decoration:none;transition:background .15s;background:{{ $couleur['bg'] }}80;"
               onmouseover="this.style.background='{{ $couleur['bg'] }}'"
               onmouseout="this.style.background='{{ $couleur['bg'] }}80'">

                {{-- Dot couleur --}}
                <div style="width:8px;height:8px;border-radius:50%;background:{{ $couleur['dot'] }};margin-top:5px;flex-shrink:0"></div>

                <div style="flex:1;min-width:0">
                    {{-- Titre --}}
                    <div style="font-size:.8rem;font-weight:700;color:{{ $couleur['txt'] }};margin-bottom:.15rem;line-height:1.3">
                        {{ $data['action_label'] ?? 'Notification' }}
                    </div>
                    {{-- Référence --}}
                    <div style="font-size:.74rem;color:#4b5563;font-family:monospace;margin-bottom:.15rem">
                        {{ $data['declaration_reference'] ?? '' }}
                        @if(!empty($data['entreprise_nom']))
                        · {{ $data['entreprise_nom'] }}
                        @endif
                    </div>
                    {{-- Commentaire court --}}
                    @if(!empty($data['commentaire']))
                    <div style="font-size:.72rem;color:#6b7280;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:240px">
                        {{ $data['commentaire'] }}
                    </div>
                    @endif
                    {{-- Temps relatif --}}
                    <div style="font-size:.68rem;color:#9ca3af;margin-top:.25rem">
                        {{ $notif->created_at->diffForHumans() }}
                    </div>
                </div>
            </a>
            @empty
            <div style="text-align:center;padding:2.5rem 1rem;color:#9ca3af;font-size:.8rem">
                <svg style="width:28px;height:28px;opacity:.3;margin:0 auto .5rem;display:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/>
                </svg>
                Aucune nouvelle notification
            </div>
            @endforelse
        </div>

        {{-- Pied --}}
        <div style="padding:.65rem 1rem;border-top:1px solid #e4e8f0;text-align:center">
            <a href="{{ route('notifications.index') }}"
               style="font-size:.76rem;font-weight:600;color:#2563eb;text-decoration:none">
                Voir toutes les notifications →
            </a>
        </div>
    </div>
</div>
