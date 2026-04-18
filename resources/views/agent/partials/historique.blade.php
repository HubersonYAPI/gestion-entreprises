{{--
    resources/views/agent/partials/historique.blade.php

    Composant réutilisable affichant la timeline d'une déclaration.
    Usage : @include('agent.partials.historique', ['historiques' => $declaration->historiques])
--}}

<div class="ic" style="margin-top:1rem">
    <div class="ic-h">
        <div style="display:flex;align-items:center;gap:.6rem">
            <div class="ic-hico" style="background:#f5f3ff;color:#7c3aed">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
            </div>
            <div class="ic-htit">Historique des actions</div>
        </div>
        <span style="font-size:.7rem;font-weight:700;background:#eff2ff;color:#2f54eb;padding:2px 8px;border-radius:20px;">
            {{ $historiques->count() }} action(s)
        </span>
    </div>

    @if($historiques->isEmpty())
        <div style="text-align:center;padding:2rem;color:#9ca3af;font-size:.8rem">
            Aucune action enregistrée.
        </div>
    @else
    <div style="padding:.75rem 1.1rem">
        {{-- Timeline --}}
        <div style="position:relative;padding-left:1.5rem">

            {{-- Ligne verticale --}}
            <div style="position:absolute;left:.4rem;top:.5rem;bottom:.5rem;width:2px;background:linear-gradient(to bottom,#e4e8f0,#e4e8f0);border-radius:99px"></div>

            @foreach($historiques->sortByDesc('created_at') as $h)
            @php
                $colors = match($h->action) {
                    'valide'           => ['dot'=>'#059669','bg'=>'#ecfdf5','txt'=>'#065f46','border'=>'#a7f3d0'],
                    'rejete'           => ['dot'=>'#dc2626','bg'=>'#fef2f2','txt'=>'#991b1b','border'=>'#fecaca'],
                    'soumis'           => ['dot'=>'#2563eb','bg'=>'#eff6ff','txt'=>'#1d4ed8','border'=>'#bfdbfe'],
                    'en_traitement'    => ['dot'=>'#d97706','bg'=>'#fffbeb','txt'=>'#92400e','border'=>'#fcd34d'],
                    'non_paye'         => ['dot'=>'#ea580c','bg'=>'#fff7ed','txt'=>'#9a3412','border'=>'#fed7aa'],
                    'paiement'         => ['dot'=>'#0d9488','bg'=>'#f0fdfa','txt'=>'#0f766e','border'=>'#99f6e4'],
                    'terminé'          => ['dot'=>'#9333ea','bg'=>'#faf5ff','txt'=>'#6b21a8','border'=>'#ddd6fe'],
                    'document_valide'  => ['dot'=>'#059669','bg'=>'#ecfdf5','txt'=>'#065f46','border'=>'#a7f3d0'],
                    'document_rejete'  => ['dot'=>'#dc2626','bg'=>'#fef2f2','txt'=>'#991b1b','border'=>'#fecaca'],
                    'creation'         => ['dot'=>'#64748b','bg'=>'#f8fafc','txt'=>'#475569','border'=>'#e2e8f0'],
                    default            => ['dot'=>'#94a3b8','bg'=>'#f8fafc','txt'=>'#64748b','border'=>'#e4e8f0'],
                };
            @endphp

            <div style="position:relative;margin-bottom:1rem;padding-left:.75rem">

                {{-- Point de la timeline --}}
                <div style="position:absolute;left:-1.1rem;top:.3rem;width:10px;height:10px;border-radius:50%;background:{{ $colors['dot'] }};border:2px solid #fff;box-shadow:0 0 0 2px {{ $colors['dot'] }}40;z-index:1"></div>

                {{-- Contenu --}}
                <div style="background:{{ $colors['bg'] }};border:1px solid {{ $colors['border'] }};border-radius:9px;padding:.75rem 1rem">

                    {{-- En-tête --}}
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:.75rem;flex-wrap:wrap;margin-bottom:.35rem">
                        <div style="display:flex;align-items:center;gap:.5rem;flex-wrap:wrap">
                            {{-- Badge action --}}
                            <span style="font-size:.72rem;font-weight:700;color:{{ $colors['txt'] }};background:{{ $colors['bg'] }};border:1px solid {{ $colors['border'] }};padding:2px 8px;border-radius:20px">
                                {{ $h->action_label }}
                            </span>

                            {{-- Transition de statut --}}
                            @if($h->ancien_statut && $h->nouveau_statut && $h->ancien_statut !== $h->nouveau_statut)
                            <span style="font-size:.7rem;color:#64748b">
                                <span style="font-family:monospace">{{ $h->ancien_statut }}</span>
                                →
                                <span style="font-family:monospace;font-weight:600;color:{{ $colors['txt'] }}">{{ $h->nouveau_statut }}</span>
                            </span>
                            @endif
                        </div>

                        {{-- Date --}}
                        <div style="text-align:right">
                            <div style="font-size:.72rem;color:#64748b;white-space:nowrap">
                                {{ $h->created_at->diffForHumans() }}
                            </div>
                            <div style="font-size:.67rem;color:#9ca3af">
                                {{ $h->created_at->format('d/m/Y à H:i') }}
                            </div>
                        </div>
                    </div>

                    {{-- Auteur --}}
                    @if($h->user)
                    <div style="display:flex;align-items:center;gap:.4rem;margin-bottom:.35rem">
                        <div style="width:18px;height:18px;border-radius:50%;background:linear-gradient(135deg,#2f54eb,#7c3aed);display:flex;align-items:center;justify-content:center;font-size:.58rem;font-weight:700;color:#fff;flex-shrink:0">
                            {{ strtoupper(substr($h->user->name, 0, 1)) }}
                        </div>
                        <span style="font-size:.75rem;font-weight:600;color:#374151">
                            {{ $h->user->name }}
                        </span>
                        @if($h->user->hasAnyRole(['AGENT','CONTROLEUR','SUPER_ADMIN']))
                        <span style="font-size:.62rem;font-weight:700;background:#eff2ff;color:#2f54eb;padding:1px 5px;border-radius:20px">
                            Admin
                        </span>
                        @endif
                    </div>
                    @else
                    <div style="font-size:.73rem;color:#9ca3af;margin-bottom:.35rem;font-style:italic">
                        Système
                    </div>
                    @endif

                    {{-- Commentaire --}}
                    @if($h->commentaire)
                    <div style="font-size:.76rem;color:#374151;background:rgba(255,255,255,.7);padding:.4rem .7rem;border-radius:6px;border-left:3px solid {{ $colors['dot'] }};margin-top:.3rem">
                        {{ $h->commentaire }}
                    </div>
                    @endif

                    {{-- IP (optionnel, visible pour SUPER_ADMIN) --}}
                    @if(Auth::user()->hasRole('SUPER_ADMIN') && $h->ip_adress)
                    <div style="font-size:.65rem;color:#9ca3af;margin-top:.35rem;font-family:monospace">
                        IP : {{ $h->ip_adress }}
                    </div>
                    @endif

                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
