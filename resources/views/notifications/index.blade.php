@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="container py-5 notifications-page">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center gap-2 flex-wrap">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="bi bi-bell me-2"></i>All Notifications
                        @if(($unreadCount ?? 0) > 0)
                            <span class="badge bg-danger rounded-pill ms-1">{{ $unreadCount }}</span>
                        @endif
                    </h5>
                    {{-- $unreadCount is the true total across all pages; the
                         old per-page check hid the button on page 2+. --}}
                    @if(($unreadCount ?? 0) > 0)
                        <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill">
                                Mark all as read
                            </button>
                        </form>
                    @endif
                </div>
                <div class="card-body p-0">
                    @if($notifications->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($notifications as $notification)
                                <div class="list-group-item list-group-item-action border-0 border-bottom px-4 py-3 {{ is_null($notification->read_at) ? 'bg-light' : '' }}">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="flex-shrink-0">
                                            @php
                                                $status = $notification->data['status'] ?? 'default';
                                                $bgClass = 'bg-primary';
                                                $icon = 'bi-info-circle';
                                                
                                                if ($status === 'approved') {
                                                    $bgClass = 'bg-success';
                                                    $icon = 'bi-check-circle';
                                                } elseif ($status === 'denied') {
                                                    $bgClass = 'bg-danger';
                                                    $icon = 'bi-x-circle';
                                                } elseif ($status === 'cancelled') {
                                                    $bgClass = 'bg-secondary';
                                                    $icon = 'bi-dash-circle';
                                                }
                                            @endphp
                                            <div class="{{ $bgClass }} text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="bi {{ $icon }}"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 min-w-0">
                                            <div class="notif-head d-flex justify-content-between align-items-start gap-2">
                                                <h6 class="mb-1 fw-bold {{ is_null($notification->read_at) ? 'text-dark' : 'text-muted' }}">
                                                    {{-- Older notification payloads may predate these keys. --}}
                                                    {{ $notification->data['message'] ?? 'Notification' }}
                                                </h6>
                                                <small class="text-muted ms-2 flex-shrink-0 notif-time">{{ $notification->created_at->diffForHumans() }}</small>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mt-2 gap-2 flex-wrap">
                                                @if(!empty($notification->data['action_url']))
                                                    <a href="{{ $notification->data['action_url'] }}" class="btn btn-sm btn-link p-0 text-decoration-none text-primary fw-bold">
                                                        View Details <i class="bi bi-arrow-right ms-1"></i>
                                                    </a>
                                                @else
                                                    <span></span>
                                                @endif
                                                @if(is_null($notification->read_at))
                                                    <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-link p-0 text-decoration-none text-muted" title="Mark as read">
                                                            <i class="bi bi-check2-all me-1"></i>Mark read
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="p-4 d-flex justify-content-center">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-bell-slash text-muted opacity-25" style="font-size: 4rem;"></i>
                            <h5 class="mt-3 text-muted">No notifications yet</h5>
                            <p class="text-muted small">We'll notify you here when there are updates to your reservations.</p>
                            <a href="{{ route('guest.dashboard') }}" class="btn btn-primary mt-3 rounded-pill px-4">
                                Back to Dashboard
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .list-group-item {
        transition: background-color 0.2s ease;
    }
    .list-group-item:hover {
        background-color: rgba(0,0,0,0.02) !important;
    }

    /* ── Mobile ──────────────────────────────────────────────
       The row is a fixed-width avatar beside a flexible body,
       with the timestamp pushed to the far right. At phone
       widths the timestamp squeezes the message into a narrow
       column, so it moves under the message instead.          */
    @media (max-width: 575.98px) {
        .notifications-page .container { padding-left: .75rem; padding-right: .75rem; }
        .notifications-page .list-group-item { padding: .9rem 1rem !important; }

        .notifications-page .notif-head {
            flex-direction: column;
            gap: 0 !important;
        }
        .notifications-page .notif-time {
            margin-left: 0 !important;
            font-size: .72rem;
            order: 2;
        }
        .notifications-page .notif-head h6 {
            font-size: .9rem;
            order: 1;
            line-height: 1.35;
        }

        /* Keep the icon from shrinking the text column further. */
        .notifications-page .rounded-circle {
            width: 34px !important;
            height: 34px !important;
            font-size: .85rem;
        }

        /* Pagination overflows at 360px with more than a few pages. */
        .notifications-page .pagination {
            flex-wrap: wrap;
            justify-content: center;
            gap: .2rem;
        }
    }
</style>
@endsection
