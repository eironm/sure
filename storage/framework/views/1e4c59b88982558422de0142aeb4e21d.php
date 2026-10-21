<?php
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
?>
<span
    class="uavatar <?php echo e($size ?? 'sm'); ?>"
    style="--uav-hue: <?php echo e($hue); ?>;"
    title="<?php echo e($user->first_name ?? $user->name); ?> <?php echo e($user->last_name ?? ''); ?>"
>
    <?php if(!empty($user->avatar) && $user->avatarUrl()): ?>
        <img src="<?php echo e($user->avatarUrl()); ?>" alt="Avatar of <?php echo e($user->first_name ?? $user->name); ?>" class="uavatar-img">
    <?php else: ?>
        <span class="uavatar-initials" aria-hidden="true"><?php echo e($initials); ?></span>
        <i class="bi bi-person-fill uavatar-watermark" aria-hidden="true"></i>
    <?php endif; ?>
</span>
<?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/components/user-avatar.blade.php ENDPATH**/ ?>