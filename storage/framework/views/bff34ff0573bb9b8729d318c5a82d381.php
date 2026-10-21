<div class="d-none d-md-flex flex-column align-items-end me-3" style="line-height: 1.2;">
    <span id="global-rt-date" class="fw-bold fs-6 text-primary" style="font-family: 'JetBrains Mono', monospace;">
        <?php echo e(now()->format('l, M j')); ?>

    </span>
    <span id="global-rt-time" class="text-muted fw-bold" style="font-family: 'JetBrains Mono', monospace; font-size: 0.75rem;">
        <?php echo e(now()->format('Y h:i:s A')); ?>

    </span>
</div>

<script>
    if (!window.RealTimeClockStarted) {
        window.RealTimeClockStarted = true;
        onPageLoad(function() {
            function updateGlobalClock() {
                const now = new Date();
                const hour = now.getHours();

                const dateEl = document.getElementById('global-rt-date');
                if (dateEl) {
                    dateEl.innerText = now.toLocaleDateString('en-US', { weekday: 'long', month: 'short', day: 'numeric' });
                }

                const timeEl = document.getElementById('global-rt-time');
                if (timeEl) {
                    let h = hour % 12 || 12;
                    const m = now.getMinutes().toString().padStart(2, '0');
                    const s = now.getSeconds().toString().padStart(2, '0');
                    const a = hour >= 12 ? 'PM' : 'AM';
                    timeEl.innerText = `${now.getFullYear()} ${h.toString().padStart(2,'0')}:${m}:${s} ${a}`;
                }
            }
            updateGlobalClock();
            setInterval(updateGlobalClock, 1000);
        });
    }
</script>
<?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/components/realtime-clock.blade.php ENDPATH**/ ?>