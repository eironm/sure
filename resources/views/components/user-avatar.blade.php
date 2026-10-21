@php
    // Deterministic hue so the same person always gets the same tile color.
    $hue = intval(fmod(abs(crc32((string) ($user->id ?? md5($user->first_name ?? $user->name ?? '')))), 360));
    $initials = '';
    $firstName = trim($user->first_name ?? '');
    $lastName = trim($user->last_name ?? '');
    if ($firstName !== '' || $lastName !== '') {
        $initials = strtoupper(mb_substr($firstName, 0, 1) . mb_substr($lastName, 0, 1));
    } elseif (isset($user->name) && trim($user->name) !== '') {
        $parts = array_filter(explode(' ', trim($user->name)));
        $initials = strtoupper(mb_substr(reset($parts), 0, 1) . mb_substr(end($parts), 0, 1));
    }
    if ($initials === '') {
        $initials = '?';
    }
@endphp
<span
    class="uavatar {{ $size ?? 'sm' }}"
    style="--uav-hue: {{ $hue }};"
    title="{{ $user->first_name ?? $user->name }} {{ $user->last_name ?? '' }}"
>
    @if (!empty($user->avatar) && $user->avatarUrl())
        <img src="{{ $user->avatarUrl() }}" alt="Avatar of {{ $user->first_name ?? $user->name }}" class="uavatar-img">
    @else
        <span class="uavatar-initials" aria-hidden="true">{{ $initials }}</span>
        <i class="bi bi-person-fill uavatar-watermark" aria-hidden="true"></i>
    @endif
</span>
