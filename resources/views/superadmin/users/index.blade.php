@extends('layouts.superadmin')

@section('title', 'User Management')
@section('heading', 'User Management')

@section('content')

<div class="page-header">
    <div>
        <h1 class="ph-title">User Management</h1>
        <p class="ph-sub">Every account on the platform, with roles, statuses and access controls.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('superadmin.dashboard') }}" class="btn btn-ghost"><i class="bi bi-arrow-left"></i>Dashboard</a>
    </div>
</div>

    @include('shared.user-management', ['panel' => 'superadmin'])
@endsection
