@auth
{{-- ================================================================
     NOTIFICATION BELL — Bootstrap 5 + Alpine.js + Laravel Echo
     ================================================================ --}}
<div
    x-data="notificationBell()"
    x-init="init()"
    class="position-relative d-flex align-items-center"
    id="notification-bell-wrapper"
>
    {{-- Bell Trigger Button --}}
    <button
        @click="toggleDropdown()"
        type="button"
        id="notification-bell-btn"
        aria-label="Notifications"
        class="btn border-0 p-2 position-relative d-flex align-items-center justify-content-center notification-bell-btn"
        style="line-height: 1; transition: all 0.3s ease;"
    >
        <i class="bi bi-bell-fill"></i>

        {{-- Unread badge --}}
        <span
            x-show="unreadCount > 0"
            x-text="unreadCount > 99 ? '99+' : unreadCount"
            x-cloak
            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow-sm"
            style="font-size: 0.65rem; min-width: 20px; height: 20px; padding: 4px; display: flex; align-items: center; justify-content: center; border: 2px solid var(--surface-card, #fff);"
        ></span>
    </button>

    <style>
        .notification-bell-btn {
            background: rgba(var(--brand-primary-rgb, 37, 99, 235), 0.1);
            color: var(--brand-primary, #2563eb);
            border: 1px solid rgba(var(--brand-primary-rgb, 37, 99, 235), 0.2) !important;
            border-radius: 10px;
            width: 40px;
            height: 40px;
            font-size: 1.25rem;
        }
        .notification-bell-btn:hover {
            background: rgba(var(--brand-primary-rgb, 37, 99, 235), 0.25);
            color: var(--brand-primary, #2563eb);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(var(--brand-primary-rgb, 37, 99, 235), 0.2);
        }

        /* ── Mobile ──────────────────────────────────────────
           The panel is anchored to the bell with a hard 340px
           width. On a 360px screen that overflows the viewport
           and clips the right-hand side, including the unread
           dots. Below 480px it detaches from the bell and spans
           the screen with a fixed margin instead.              */
           It stays anchored under the bell (switching to position:fixed
           would detach it and land differently in each layout); only the
           width is constrained. Growing leftward from right:0 cannot
           introduce horizontal scroll, since that edge is already inside
           the viewport. */
        @media (max-width: 479.98px) {
            .notification-dropdown {
                width: calc(100vw - 1.5rem) !important;
                max-width: calc(100vw - 1.5rem) !important;
            }
        }

        @media (hover: none) and (pointer: coarse) {
            .notification-bell-btn:hover {
                transform: none;
                box-shadow: none;
            }
        }
    </style>

    {{-- Dropdown Panel --}}
    <div
        x-show="open"
        x-cloak
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="position-absolute bg-white rounded-3 shadow notification-dropdown"
        style="
            top: calc(100% + 10px);
            right: 0;
            width: 340px;
            z-index: 1060;
            border: 1px solid rgba(0,0,0,0.1);
            overflow: hidden;
        "
    >
        {{-- Header Row --}}
        <div class="d-flex align-items-center justify-content-between px-3 py-2" style="border-bottom:1px solid rgba(0,0,0,0.08);">
            <span class="fw-bold text-dark d-flex align-items-center gap-2" style="font-size:0.9rem;">
                <i class="bi bi-bell-fill text-warning"></i>
                Notifications
                <span x-show="unreadCount > 0" x-cloak class="badge bg-danger rounded-pill" style="font-size:0.6rem;" x-text="unreadCount"></span>
            </span>
            <button
                x-show="unreadCount > 0"
                x-cloak
                @click.stop="markAllAsRead()"
                type="button"
                class="btn btn-link btn-sm p-0 text-decoration-none fw-semibold"
                style="font-size:0.75rem;"
            >Mark all read</button>
        </div>

        {{-- Notification List --}}
        <div style="max-height:360px; overflow-y:auto;">

            {{-- Empty state --}}
            <div x-show="notifications.length === 0" x-cloak class="text-center py-5 text-muted">
                <i class="bi bi-bell-slash d-block mb-2 opacity-25" style="font-size:2.5rem;"></i>
                <p class="small mb-0">No notifications yet</p>
            </div>

            {{-- List items --}}
            <template x-for="notif in notifications" :key="notif.id">
                <div
                    class="border-bottom"
                    :style="!notif.read_at ? 'background:#f0f8ff;' : ''"
                >
                    <div
                        @click="markAsRead(notif.id)"
                        class="d-flex align-items-start gap-3 px-3 py-3"
                        style="color:inherit; transition:background 0.15s; cursor:pointer;"
                        onmouseover="this.style.background='#f8f9fa'"
                        onmouseout="this.style.background=''"
                    >
                        {{-- Icon --}}
                        <div class="flex-shrink-0 mt-1">
                            <div
                                class="rounded-circle d-flex align-items-center justify-content-center"
                                :class="{
                                    'bg-success-subtle text-success': notif.data.status === 'approved',
                                    'bg-danger-subtle text-danger':   notif.data.status === 'denied',
                                    'bg-primary-subtle text-primary': notif.data.status === 'submitted',
                                    'bg-secondary-subtle text-secondary': notif.data.status === 'cancelled',
                                    'bg-warning-subtle text-warning': !['approved','denied','submitted','cancelled'].includes(notif.data.status)
                                }"
                                style="width:32px;height:32px;font-size:0.85rem;"
                            >
                                <i
                                    :class="{
                                        'bi bi-check-circle-fill': notif.data.status === 'approved',
                                        'bi bi-x-circle-fill':     notif.data.status === 'denied',
                                        'bi bi-info-circle-fill':  notif.data.status === 'submitted',
                                        'bi bi-dash-circle-fill':  notif.data.status === 'cancelled',
                                        'bi bi-bell-fill':         !['approved','denied','submitted','cancelled'].includes(notif.data.status)
                                    }"
                                ></i>
                            </div>
                        </div>

                        {{-- Text --}}
                        <div class="flex-grow-1 min-w-0">
                            <p
                                class="mb-1 fw-semibold lh-sm"
                                :class="!notif.read_at ? 'text-dark' : 'text-muted'"
                                style="font-size:0.82rem;"
                                x-text="getNotifMessage(notif)"
                            ></p>
                            {{-- Denial reason shown separately --}}
                            <template x-if="notif.data.status === 'denied' && getDenialReason(notif)">
                                <p class="mb-1 fst-italic" style="font-size:0.75rem; color:#dc3545; line-height:1.3;">
                                    <i class="bi bi-quote me-1"></i>
                                    <span x-text="getDenialReason(notif)"></span>
                                </p>
                            </template>
                            <p class="mb-0 text-muted" style="font-size:0.7rem;" x-text="timeAgo(notif.created_at)"></p>
                        </div>

                        {{-- Unread dot --}}
                        <div x-show="!notif.read_at" class="flex-shrink-0 mt-2">
                            <span class="rounded-circle bg-primary d-inline-block" style="width:8px;height:8px;"></span>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        {{-- Footer --}}
        <div class="text-center py-2 px-3" style="border-top:1px solid rgba(0,0,0,0.08);">
            <span class="text-muted" style="font-size:0.7rem;" x-text="notifications.length ? 'Showing ' + notifications.length + ' notification' + (notifications.length !== 1 ? 's' : '') : ''"></span>
        </div>
    </div>
</div>

<script>
function notificationBell() {
    return {
        open: false,
        notifications: [],
        unreadCount: 0,
        _pollTimer: null,

        init() {
            this.fetchNotifications();

            // Polling fallback every 30s (works even without Reverb)
            this._pollTimer = setInterval(() => this.fetchNotifications(), 30000);

            // Real-time via Laravel Echo + Reverb
            // On broadcast, do a full re-fetch from server to avoid duplicates
            const self = this;
            if (window.Echo) {
                window.Echo.private('App.Models.User.{{ Auth::id() }}')
                    .notification(function(notification) {
                        // Show native browser notification
                        if (window.Notification && Notification.permission === 'granted') {
                            new Notification('{{ branding('system_name', 'Platform') }} — Reservation Update', {
                                body: notification.message || 'Your reservation status has changed.',
                                icon: '{{ brand_logo() }}'
                            });
                        }
                        // Re-fetch the full list from server (avoids duplicates)
                        self.fetchNotifications();
                    });
            }

            // Request browser notification permission
            if (window.Notification && Notification.permission === 'default') {
                Notification.requestPermission();
            }
        },

        // Toggle dropdown and refresh data when opening
        toggleDropdown() {
            this.open = !this.open;
            if (this.open) {
                this.fetchNotifications();
            }
        },

        async fetchNotifications() {
            try {
                const res = await fetch('{{ route('notifications.index') }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                if (!res.ok) return;
                const data = await res.json();
                this.notifications = data.notifications;
                this.unreadCount   = data.unread_count;
            } catch (e) {
                console.warn('[NotificationBell] fetch error:', e);
            }
        },

        async markAsRead(id) {
            try {
                await fetch('/notifications/' + id + '/mark-as-read', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                // Update locally without re-fetching
                const notif = this.notifications.find(n => n.id === id);
                if (notif && !notif.read_at) {
                    notif.read_at = new Date().toISOString();
                    this.unreadCount = Math.max(0, this.unreadCount - 1);
                }
            } catch (e) {
                console.warn('[NotificationBell] markAsRead error:', e);
            }
        },

        async markAllAsRead() {
            try {
                await fetch('{{ route('notifications.markAllAsRead') }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                this.notifications.forEach(n => n.read_at = n.read_at || new Date().toISOString());
                this.unreadCount = 0;
            } catch (e) {
                console.warn('[NotificationBell] markAllAsRead error:', e);
            }
        },



        // Extract the main message without the denial reason suffix
        getNotifMessage(notif) {
            const msg = notif.data.message || '';
            if (notif.data.status === 'denied' && msg.includes(' Reason: ')) {
                return msg.split(' Reason: ')[0] + '.';
            }
            return msg;
        },

        // Extract just the denial reason from the message
        getDenialReason(notif) {
            const msg = notif.data.message || '';
            if (notif.data.status === 'denied' && msg.includes(' Reason: ')) {
                return msg.split(' Reason: ').slice(1).join(' Reason: ');
            }
            return '';
        },

        timeAgo(dateString) {
            const diff = Math.floor((Date.now() - new Date(dateString)) / 1000);
            if (diff < 60)    return 'Just now';
            if (diff < 3600)  return Math.floor(diff / 60)   + 'm ago';
            if (diff < 86400) return Math.floor(diff / 3600)  + 'h ago';
            if (diff < 604800)return Math.floor(diff / 86400) + 'd ago';
            return new Date(dateString).toLocaleDateString();
        }
    };
}
</script>
@endauth
