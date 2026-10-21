@extends('layouts.superadmin')

@section('title', 'Audit Logs')

@section('content')
<div class="page-header">
    <div>
        <h1 class="ph-title">Audit Logs</h1>
        <p class="ph-sub">A complete record of administrative actions performed across the platform.</p>
    </div>
</div>

<div class="stat-grid" style="grid-template-columns: repeat(2, 1fr); margin-bottom: 1.25rem;">
    <div class="stat-card stat-side">
        <span class="stat-tile info"><i class="bi bi-activity"></i></span>
        <div class="stat-body">
            <div class="stat-top"><span class="stat-label">Total Logs</span></div>
            <div class="stat-value">{{ $logs->total() }}</div>
            <div class="stat-label" style="font-weight:500; text-transform:none; color:var(--info);">System activity recorded</div>
        </div>
    </div>
    <div class="stat-card stat-side">
        <span class="stat-tile ok"><i class="bi bi-clock-history"></i></span>
        <div class="stat-body">
            <div class="stat-top"><span class="stat-label">Latest Activity</span></div>
            <div class="stat-value" style="font-size:1.25rem;">{{ $logs->first() ? $logs->first()->created_at->diffForHumans() : 'No activity yet' }}</div>
            <div class="stat-label" style="font-weight:500; text-transform:none; color:var(--ok);">Real-time tracking active</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-head">
        <div class="ch-title"><i class="bi bi-shield-check-fill"></i> System Activity Logs</div>
        <button class="btn btn-ghost btn-sm" onclick="window.print()">
            <i class="bi bi-printer"></i> Export
        </button>
    </div>

    {{-- Filter toolbar --}}
    <form method="GET" action="{{ url()->current() }}" class="toolbar">
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" name="search" class="form-control" placeholder="Search actor, details, IP…"
                   value="{{ request('search') }}">
        </div>
        <select name="action" class="form-select">
            <option value="">All actions</option>
            @foreach(\App\Models\AuditLog::ACTION_MAP as $key => $meta)
                <option value="{{ $key }}" {{ request('action') === $key ? 'selected' : '' }}>{{ $meta['label'] }}</option>
            @endforeach
        </select>
        <input type="date" name="from" class="form-control" value="{{ request('from') }}" title="From date">
        <input type="date" name="to" class="form-control" value="{{ request('to') }}" title="To date">
        <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-funnel-fill"></i>Filter</button>
        @if(request()->hasAny(['search', 'action', 'from', 'to']))
            <a href="{{ url()->current() }}" class="btn btn-ghost btn-sm">Clear</a>
        @endif
    </form>

    <div class="table-responsive">
        <table class="plain">
            <thead>
                <tr>
                    <th style="width: 80px;">Log ID</th>
                    <th>Administrator</th>
                    <th style="width: 140px;">Action</th>
                    <th style="width: 160px;">Target</th>
                    <th>Event Details</th>
                    <th style="width: 140px;">IP Address</th>
                    <th style="width: 150px;">Timestamp</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td><span class="mono muted">#{{ str_pad($log->id, 5, '0', STR_PAD_LEFT) }}</span></td>
                        <td>
                            <div class="who">
                                <x-user-avatar :user="$log->admin" size="sm" />
                                <div>
                                    <div class="who-name">{{ $log->actorName() }}</div>
                                    <div class="who-sub">{{ $log->admin->email ?? '—' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @php
                                $badgeClass = match($log->actionClass()) {
                                    'created', 'activated' => 'badge-ok',
                                    'deleted', 'deactivated' => 'badge-bad',
                                    'updated' => 'badge-soft',
                                    default => 'badge-neutral',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">
                                <i class="bi {{ $log->actionIcon() }}"></i> {{ $log->actionLabel() }}
                            </span>
                        </td>
                        <td>
                            <div>
                                <span style="font-weight:600; color:var(--ink-1); font-size:.82rem;">{{ class_basename($log->target_type) ?: $log->target_type }}</span>
                                <div class="who-sub mono">ID: #{{ $log->target_id }}</div>
                            </div>
                        </td>
                        <td style="max-width:320px; color:var(--ink-2);">{{ $log->details }}</td>
                        <td><span class="badge badge-neutral mono">{{ $log->ip_address }}</span></td>
                        <td>
                            <span style="font-weight:600; color:var(--ink-1); font-size:.82rem;">{{ $log->created_at->format('M d, Y') }}</span>
                            <span class="who-sub mono" style="display:block;">{{ $log->created_at->format('H:i:s') }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty">
                                <i class="bi bi-wind"></i>
                                <strong>No activity logs found</strong>
                                <span>System actions will appear here once recorded.</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($logs->hasPages())
        <div class="card-body" style="border-top: 1px solid var(--line);">
            {{ $links ?? $logs->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
