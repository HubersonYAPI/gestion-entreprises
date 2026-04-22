<div class="pg">

    <div class="pg-hd">
        <div>
            <div class="pg-title">Notifications</div>
            <div class="pg-ref">Toutes vos alertes et mises à jour.</div>
        </div>
        @if(Auth::user()->unreadNotifications->count() > 0)
        <form method="POST" action="{{ route('notifications.markAllAsRead') }}">
            @csrf
            <button type="submit" class="btn-back">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                Tout marquer comme lu
            </button>
        </form>
        @endif
    </div>

    @if(session('success'))
    <div class="a-ok">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <polyline points="20 6 9 17 4 12"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="card">
        @forelse($notifications as $notif)
        @php
            $data   = $notif->data;
            $isRead = !is_null($notif->read_at);
            $couleur = match($data['couleur'] ?? 'gray') {
                'green'  => ['dot'=>'#059669','bg'=>'#ecfdf5','txt'=>'#065f46'],
                'red'    => ['dot'=>'#dc2626','bg'=>'#fef2f2','txt'=>'#991b1b'],
                'blue'   => ['dot'=>'#2563eb','bg'=>'#eff6ff','txt'=>'#1d4ed8'],
                'yellow' => ['dot'=>'#ca8a04','bg'=>'#fefce8','txt'=>'#92400e'],
                'orange' => ['dot'=>'#ea580c','bg'=>'#fff7ed','txt'=>'#9a3412'],
                'teal'   => ['dot'=>'#0d9488','bg'=>'#f0fdfa','txt'=>'#0f766e'],
                'purple' => ['dot'=>'#9333ea','bg'=>'#faf5ff','txt'=>'#6b21a8'],
                default  => ['dot'=>'#64748b','bg'=>'#f8fafc','txt'=>'#475569'],
            };
        @endphp

        <div style="display:flex;align-items:flex-start;gap:1rem;padding:1rem 1.25rem;border-bottom:1px solid #e4e8f0;background:{{ $isRead ? '#fff' : $couleur['bg'].'40' }};transition:background .15s"
             onmouseover="this.style.background='{{ $couleur['bg'].'60' }}'"
             onmouseout="this.style.background='{{ $isRead ? '#fff' : $couleur['bg'].'40' }}'">

            {{-- Indicateur lu/non-lu --}}
            <div style="width:10px;height:10px;border-radius:50%;background:{{ $isRead ? '#e5e7eb' : $couleur['dot'] }};margin-top:5px;flex-shrink:0"></div>

            {{-- Contenu --}}
            <div style="flex:1">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;flex-wrap:wrap">
                    <div>
                        <div style="font-size:.84rem;font-weight:{{ $isRead ? '500' : '700' }};color:{{ $couleur['txt'] }};margin-bottom:.2rem">
                            {{ $data['action_label'] ?? 'Notification' }}
                        </div>
                        <div style="font-size:.78rem;color:#4b5563;margin-bottom:.3rem">
                            <span class="ref">{{ $data['declaration_reference'] ?? '' }}</span>
                            @if(!empty($data['entreprise_nom']))
                                · {{ $data['entreprise_nom'] }}
                            @endif
                            @if(!empty($data['declencheur_nom']))
                                · <span style="color:#9ca3af">par {{ $data['declencheur_nom'] }}</span>
                            @endif
                        </div>
                        @if(!empty($data['commentaire']))
                        <div style="font-size:.76rem;color:#6b7280;background:#f1f5f9;padding:.4rem .7rem;border-radius:6px;border-left:3px solid {{ $couleur['dot'] }};max-width:500px">
                            {{ $data['commentaire'] }}
                        </div>
                        @endif
                    </div>
                    <div style="text-align:right;flex-shrink:0">
                        <div style="font-size:.72rem;color:#9ca3af;white-space:nowrap">
                            {{ $notif->created_at->diffForHumans() }}
                        </div>
                        <div style="font-size:.68rem;color:#d1d5db">
                            {{ $notif->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="acts">
                @if(!$isRead)
                <a href="{{ route('notifications.markAsRead', $notif->id) }}"
                   class="bi bi-ok" title="Marquer comme lu">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </a>
                @endif
                <form method="POST" action="{{ route('notifications.destroy', $notif->id) }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="bi bi-rej" title="Supprimer">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        @empty
        <div class="empty">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                <path d="M13.73 21a2 2 0 01-3.46 0"/>
            </svg>
            Aucune notification.
        </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
    <div class="pager">{{ $notifications->links() }}</div>
    @endif

</div>