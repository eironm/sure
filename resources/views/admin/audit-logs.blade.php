@extends('layouts.admin')

@section('title', 'Audit Logs')
@section('heading', 'Audit Logs')

@section('content')
<style>
    /* Sleek Professional Audit Logs Styles */
    .audit-container {
    }

    .audit-stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.08);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; height: 3px;
        background: linear-gradient(90deg, var(--accent-color, #7c3aed), #3b82f6);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .stat-card:hover::before { opacity: 1; }

    .audit-panel {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }

    .audit-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        background: linear-gradient(to right, rgba(248, 250, 252, 0.8), rgba(255, 255, 255, 0.5));
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .audit-header h5 {
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        font-size: 1.25rem;
        letter-spacing: -0.02em;
    }

    /* Modern Data Grid */
    .table-custom {
        width: 100%;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-custom th {
        background: rgba(248, 250, 252, 0.6);
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-size: 0.75rem;
        padding: 1rem 1.5rem;
        border-bottom: 2px solid #e2e8f0;
    }

    .table-custom td {
        padding: 1.25rem 1.5rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        transition: background-color 0.2s ease;
    }

    .table-custom tbody tr {
        transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
    }

    .table-custom tbody tr:hover {
        background-color: rgba(248, 250, 252, 0.8);
        transform: scale(1.002);
        box-shadow: inset 4px 0 0 var(--accent-color, #7c3aed);
    }

    /* Badges & Elements */
    .log-id {
        font-family: 'JetBrains Mono', monospace;
        font-weight: 700;
        color: #94a3b8;
        font-size: 0.85rem;
    }

    .admin-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .admin-avatar {
        width: 36px; height: 36px;
        background: linear-gradient(135deg, #f8fafc, #e2e8f0);
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; color: #475569;
        font-size: 0.85rem;
    }

    .admin-name {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.95rem;
        line-height: 1.2;
    }
    .admin-email {
        font-size: 0.75rem;
        color: #64748b;
    }

    .action-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }
    
    .action-created { background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); }
    .action-updated { background: rgba(59, 130, 246, 0.1); color: #3b82f6; border: 1px solid rgba(59, 130, 246, 0.2); }
    .action-deleted { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }
    .action-activated { background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); }
    .action-deactivated { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }
    .action-other { background: rgba(100, 116, 139, 0.1); color: #64748b; border: 1px solid rgba(100, 116, 139, 0.2); }

    .target-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.4rem 0.75rem;
        display: inline-block;
    }
    .target-type { font-weight: 700; color: #334155; font-size: 0.8rem; }
    .target-id { font-family: 'JetBrains Mono', monospace; font-size: 0.75rem; color: #94a3b8; }

    .details-text {
        font-size: 0.85rem;
        color: #475569;
        line-height: 1.5;
        max-width: 320px;
    }

    .ip-badge {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.75rem;
        color: #64748b;
        background: #f1f5f9;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
    }

    .time-info {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }
    .time-date {
        font-weight: 600;
        color: #334155;
        font-size: 0.85rem;
    }
    .time-exact {
        font-size: 0.75rem;
        color: #94a3b8;
        font-family: 'JetBrains Mono', monospace;
    }

    /* Empty State */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }
    .empty-icon {
        font-size: 3rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }
    
    .pagination-container {
        padding: 1.5rem 2rem;
        border-top: 1px solid #f1f5f9;
        background: rgba(255, 255, 255, 0.5);
    }
</style>

<div class="audit-container">

    <div class="audit-stats-row">
        <div class="stat-card">
            <div class="text-muted small fw-bold text-uppercase mb-1">Total Logs</div>
            <div class="fs-3 fw-bold text-dark">{{ $logs->total() }}</div>
            <div class="mt-2 text-primary small fw-semibold"><i class="bi bi-activity"></i> System Activity Recorded</div>
        </div>
        <div class="stat-card">
            <div class="text-muted small fw-bold text-uppercase mb-1">Latest Activity</div>
            <div class="fs-5 fw-bold text-dark mt-2">{{ $logs->first() ? $logs->first()->created_at->diffForHumans() : 'No activity yet' }}</div>
            <div class="mt-2 text-success small fw-semibold"><i class="bi bi-clock-history"></i> Real-time tracking active</div>
        </div>
    </div>

    <div class="audit-panel">
        <div class="audit-header">
            <h5 class="mb-0"><i class="bi bi-shield-check text-primary me-2"></i> System Activity Logs</h5>
            <button class="btn btn-sm btn-outline-secondary rounded-pill fw-semibold px-3" onclick="window.print()">
                <i class="bi bi-printer me-1"></i> Export
            </button>
        </div>

        {{-- Filter toolbar --}}
        <form method="GET" action="{{ url()->current() }}" class="table-toolbar" style="border-radius: 0;">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search actor, details, IP…"
                   value="{{ request('search') }}">
            <select name="action" class="form-select form-select-sm" style="max-width: 170px;">
                <option value="">All actions</option>
                @foreach(\App\Models\AuditLog::ACTION_MAP as $key => $meta)
                    <option value="{{ $key }}" {{ request('action') === $key ? 'selected' : '' }}>{{ $meta['label'] }}</option>
                @endforeach
            </select>
            <input type="date" name="from" class="form-control form-control-sm" style="max-width: 160px;" value="{{ request('from') }}" title="From date">
            <input type="date" name="to" class="form-control form-control-sm" style="max-width: 160px;" value="{{ request('to') }}" title="To date">
            <button type="submit" class="btn btn-sm btn-sure px-3"><i class="bi bi-funnel me-1"></i>Filter</button>
            @if(request()->hasAny(['search', 'action', 'from', 'to']))
                <a href="{{ url()->current() }}" class="btn btn-sm btn-light">Clear</a>
            @endif
        </form>

        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th style="width: 80px;">Log ID</th>
                        <th>Administrator</th>
                        <th style="width: 140px;">Action</th>
                        <th style="width: 160px;">Target</th>
                        <th>Event Details</th>
                        <th style="width: 140px;">IP Address</th>
                        <th class="text-end" style="width: 150px;">Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td><span class="log-id">#{{ str_pad($log->id, 5, '0', STR_PAD_LEFT) }}</span></td>
                            <td>
                                <div class="admin-info">
                                    <x-user-avatar :user="$log->admin" size="sm" />
                                    <div>
                                        <div class="admin-name">{{ $log->actorName() }}</div>
                                        <div class="admin-email">{{ $log->admin->email ?? '—' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="action-badge action-{{ $log->actionClass() }}">
                                    <i class="bi {{ $log->actionIcon() }}"></i> {{ $log->actionLabel() }}
                                </span>
                            </td>
                            <td>
                                <div class="target-box shadow-sm">
                                    <div class="target-type">{{ class_basename($log->target_type) ?: $log->target_type }}</div>
                                    <div class="target-id">ID: #{{ $log->target_id }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="details-text">
                                    {{ $log->details }}
                                </div>
                            </td>
                            <td><span class="ip-badge">{{ $log->ip_address }}</span></td>
                            <td>
                                <div class="time-info">
                                    <span class="time-date">{{ $log->created_at->format('M d, Y') }}</span>
                                    <span class="time-exact">{{ $log->created_at->format('H:i:s') }}</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="bi bi-wind empty-icon"></i>
                                    <h5 class="fw-bold text-secondary">No activity logs found</h5>
                                    <p class="text-muted">System actions will appear here once recorded.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
        <div class="pagination-container">
            {{ $logs->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@endsection
