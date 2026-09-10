<?php require_once 'app/views/layouts/header.php'; ?>

<style>
    .qr-container {
        background: white;
        padding: 20px;
        border-radius: 20px;
        display: inline-block;
        margin: 20px auto;
    }
</style>

<div class="section-header mt-2 mb-4 d-flex align-items-center">
    <a href="<?= BASE_URL ?>/index.php?controller=customer&action=dashboard" class="text-cyan" style="font-size: 1.2rem; margin-right: 15px;"><i class="fas fa-arrow-left"></i></a>
    <h3 class="section-title mb-0">Your Ticket</h3>
</div>

<div class="card" style="text-align: center; padding: 40px 20px;">
    
    <h2 style="color: var(--secondary-color); margin-bottom: 5px;"><?= htmlspecialchars($queue['salon_name']) ?></h2>
    <p class="text-muted" style="font-size: 1.1rem; margin-bottom: 30px;"><?= htmlspecialchars($queue['service_name']) ?></p>

    <div class="qr-container">
        <!-- Render QR using an external API for the demo -->
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?= urlencode($queue['qr_token']) ?>" alt="QR Code" style="width: 200px; height: 200px;">
    </div>
    
    <p style="font-family: monospace; font-size: 1.2rem; letter-spacing: 2px; margin-top: 10px; color: var(--text-muted);">
        <?= htmlspecialchars($queue['qr_token']) ?>
    </p>

    <div style="display: flex; justify-content: center; gap: 40px; margin-top: 30px; margin-bottom: 30px;">
        <div>
            <div style="font-size: 0.9rem; color: var(--text-muted); text-transform: uppercase;">Status</div>
            <div style="font-size: 1.2rem; font-weight: bold; color: var(--success);"><?= ucfirst(str_replace('_', ' ', $queue['status'])) ?></div>
        </div>
        <div>
            <div style="font-size: 0.9rem; color: var(--text-muted); text-transform: uppercase;">Ahead</div>
            <div style="font-size: 1.2rem; font-weight: bold;"><?= $queue['customers_ahead'] ?></div>
        </div>
        <div>
            <div style="font-size: 0.9rem; color: var(--text-muted); text-transform: uppercase;">Est Wait</div>
            <div style="font-size: 1.2rem; font-weight: bold;"><?= $queue['estimated_wait'] ?> min</div>
        </div>
    </div>
    
    <div style="background: rgba(255, 255, 255, 0.03); padding: 15px; border-radius: 12px; margin-bottom: 30px;">
        <p class="mb-0" style="font-size: 0.9rem;">Show this QR code to the barber when it is your turn.</p>
    </div>

    <?php if($queue['status'] === 'waiting'): ?>
        <form action="<?= BASE_URL ?>/index.php?controller=queue&action=cancel" method="POST">
            <input type="hidden" name="queue_id" value="<?= $queue['id'] ?>">
            <button type="submit" class="btn btn-outline text-danger confirm-action" style="padding: 12px 30px; border-radius: 50px;">Cancel Booking</button>
        </form>
    <?php endif; ?>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>
