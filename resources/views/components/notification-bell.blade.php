{{--
    resources/views/components/notification-bell.blade.php
    Polling 30s + styles identiques à la version originale
--}}

<div
    x-data="notifBell({{ Auth::user()->unreadNotifications->count() }})"
    x-init="init()"
    @click.away="open = false"
    style="position:relative">

    {{-- ── Toast (téléporté dans body pour éviter le z-index de la sidebar) ── --}}
    <template x-teleport="body">
        <div
            x-show="toast"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            style="position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999;
                   background:#1e293b;color:#fff;padding:.75rem 1.1rem;border-radius:10px;
                   box-shadow:0 8px 32px rgba(0,0,0,.25);font-size:.8rem;font-weight:600;
                   display:flex;align-items:center;gap:.6rem;max-width:320px;pointer-events:none">
            <svg style="width:16px;height:16px;color:#60a5fa;flex-shrink:0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                <path d="M13.73 21a2 2 0 01-3.46 0"/>
            </svg>
            <span x-text="toastMsg"></span>
        </div>
    </template>

    {{-- ── Bouton cloche ── --}}
    <button @click="open = !open"
            style="position:relative;width:32px;height:32px;border-radius:7px;background:var(--bg,#f0f2f8);border:1px solid var(--border,#e4e8f0);display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--t2,#4b5563);transition:all .15s"
            title="Notifications">
        <svg style="width:15px;height:15px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/>
            <path d="M13.73 21a2 2 0 01-3.46 0"/>
        </svg>
        {{-- Badge --}}
        <template x-if="count > 0">
            <span x-text="count > 99 ? '99+' : count"
                  style="position:absolute;top:-4px;right:-4px;min-width:16px;height:16px;border-radius:99px;
                         background:#ef4444;color:#fff;font-size:.6rem;font-weight:800;
                         display:flex;align-items:center;justify-content:center;padding:0 3px;
                         border:1.5px solid #fff;line-height:1">
            </span>
        </template>
    </button>

    {{-- ── Dropdown ── --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         style="display:none;position:absolute;top:calc(100% + 8px);right:0;width:340px;
                background:#fff;border:1px solid #e4e8f0;border-radius:12px;
                box-shadow:0 16px 48px rgba(0,0,0,.14);z-index:300;overflow:hidden">

        {{-- En-tête --}}
        <div style="display:flex;align-items:center;justify-content:space-between;padding:.8rem 1rem;border-bottom:1px solid #e4e8f0">
            <div style="font-size:.82rem;font-weight:700;color:#111827">
                Notifications
                <template x-if="count > 0">
                    <span style="font-size:.68rem;font-weight:700;background:#eff6ff;color:#2563eb;padding:1px 6px;border-radius:20px;margin-left:.3rem"
                          x-text="count + (count > 1 ? ' non lues' : ' non lue')">
                    </span>
                </template>
            </div>
            <template x-if="count > 0">
                <form method="POST" action="{{ route('notifications.markAllAsRead') }}">
                    @csrf
                    <button type="submit" style="font-size:.71rem;font-weight:600;color:#2563eb;background:none;border:none;cursor:pointer;padding:0">
                        Tout marquer lu
                    </button>
                </form>
            </template>
        </div>

        {{-- Liste --}}
        <div style="max-height:360px;overflow-y:auto">

            {{-- Vide --}}
            <template x-if="items.length === 0">
                <div style="text-align:center;padding:2.5rem 1rem;color:#9ca3af;font-size:.8rem">
                    <svg style="width:28px;height:28px;opacity:.3;margin:0 auto .5rem;display:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/>
                    </svg>
                    Aucune nouvelle notification
                </div>
            </template>

            {{-- Items --}}
            <template x-for="n in items" :key="n.id">
                <a :href="n.read_url"
                   @mouseenter="$el.style.background = colors(n.couleur).bg"
                   @mouseleave="$el.style.background = colors(n.couleur).bgFaded"
                   :style="'display:flex;align-items:flex-start;gap:.75rem;padding:.8rem 1rem;text-decoration:none;transition:background .15s;background:' + colors(n.couleur).bgFaded">

                    {{-- Dot --}}
                    <div :style="'width:8px;height:8px;border-radius:50%;margin-top:5px;flex-shrink:0;background:' + colors(n.couleur).dot"></div>

                    <div style="flex:1;min-width:0">

                        {{-- Titre --}}
                        <div :style="'font-size:.8rem;font-weight:700;margin-bottom:.15rem;line-height:1.3;color:' + colors(n.couleur).txt"
                             x-text="n.action_label">
                        </div>

                        {{-- Référence · Entreprise --}}
                        <div style="font-size:.74rem;color:#4b5563;font-family:monospace;margin-bottom:.15rem">
                            <span x-text="n.declaration_reference + (n.entreprise_nom ? ' · ' + n.entreprise_nom : '')"></span>
                        </div>

                        {{-- Commentaire --}}
                        <template x-if="n.commentaire">
                            <div x-text="n.commentaire"
                                 style="font-size:.72rem;color:#6b7280;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:240px">
                            </div>
                        </template>

                        {{-- Temps relatif --}}
                        <div x-text="n.created_at_human"
                             style="font-size:.68rem;color:#9ca3af;margin-top:.25rem">
                        </div>
                    </div>
                </a>
            </template>
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

<script>
function notifBell(initialCount) {
    return {
        open:     false,
        count:    initialCount,
        items:    [],
        toast:    false,
        toastMsg: '',

        init() {
            // Chargement immédiat des items
            this.fetchNotifs(false);
            // Polling toutes les 10s
            setInterval(() => this.fetchNotifs(true), 10000);
        },

        async fetchNotifs(notify) {
            try {
                const res = await fetch('{{ route('notifications.poll') }}', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (!res.ok) return;

                const data = await res.json();

                // Nouvelle(s) notification(s) arrivée(s) depuis le dernier poll
                if (notify && data.count > this.count) {
                    this.showToast(data.count - this.count);
                }

                this.count = data.count;
                this.items = data.items;

            } catch (_) { /* silencieux */ }
        },

        showToast(nb) {
            this.toastMsg = nb === 1
                ? 'Vous avez une nouvelle notification'
                : `Vous avez ${nb} nouvelles notifications`;
            this.toast = true;
            setTimeout(() => { this.toast = false; }, 4000);
        },

        // Renvoie bg (hover), bgFaded (repos) + dot + txt
        // bgFaded = bg + '80' en héxa = ~50% opacité, identique à la version Blade originale
        colors(couleur) {
            const map = {
                green:  { bg:'#ecfdf5', bgFaded:'#ecfdf580', dot:'#059669', txt:'#065f46' },
                red:    { bg:'#fef2f2', bgFaded:'#fef2f280', dot:'#dc2626', txt:'#991b1b' },
                blue:   { bg:'#eff6ff', bgFaded:'#eff6ff80', dot:'#2563eb', txt:'#1d4ed8' },
                yellow: { bg:'#fefce8', bgFaded:'#fefce880', dot:'#ca8a04', txt:'#92400e' },
                orange: { bg:'#fff7ed', bgFaded:'#fff7ed80', dot:'#ea580c', txt:'#9a3412' },
                teal:   { bg:'#f0fdfa', bgFaded:'#f0fdfa80', dot:'#0d9488', txt:'#0f766e' },
                purple: { bg:'#faf5ff', bgFaded:'#faf5ff80', dot:'#9333ea', txt:'#6b21a8' },
                gray:   { bg:'#f8fafc', bgFaded:'#f8fafc80', dot:'#64748b', txt:'#475569' },
            };
            return map[couleur] ?? map.gray;
        }
    }
}
</script>