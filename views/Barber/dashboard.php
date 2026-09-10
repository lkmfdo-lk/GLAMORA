<?php require_once 'app/views/layouts/header.php'; ?>
<?php
$currentCustomer = null;
$waitingList = [];
foreach($myQueue as $entry) {
    if ($entry['status'] === 'in_service') $currentCustomer = $entry;
    elseif ($entry['status'] === 'waiting') $waitingList[] = $entry;
}
?>

<!-- Currently Serving -->
<div class="section-header mt-2 mb-3">
    <h3 class="section-title">Currently Serving</h3>
    <span style="font-size: 0.8rem; color: var(--text-muted);"><?= count($waitingList) ?> waiting</span>
</div>

<div class="card mb-4" style="<?= $currentCustomer ? 'border: 1px solid rgba(69,227,211,0.4);' : '' ?>">
    <div class="card-body" style="padding: 24px;">
        <?php if($currentCustomer): ?>
            <p class="text-muted" style="font-size: 0.85rem; margin-bottom: 4px;">Customer</p>
            <h3 style="font-weight: 700; font-size: 1.4rem; margin-bottom: 4px;"><?= htmlspecialchars($currentCustomer['customer_name']) ?></h3>
            <p class="text-muted" style="margin-bottom: 20px;"><?= htmlspecialchars($currentCustomer['service_name']) ?></p>
            <form action="<?= BASE_URL ?>/index.php?controller=qr&action=finishService" method="POST">
                <input type="hidden" name="queue_id" value="<?= $currentCustomer['id'] ?>">
                <button type="submit" class="btn btn-primary btn-block confirm-action" style="padding: 14px; border-radius: 12px; font-weight: 700;">
                    Finish Service
                </button>
            </form>
        <?php else: ?>
            <p class="text-muted" style="text-align: center; padding: 10px 0; margin: 0;">No customer in service right now.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Waiting Queue -->
<div class="section-header mb-3">
    <h3 class="section-title">Queue</h3>
    <a href="<?= BASE_URL ?>/index.php?controller=qr&action=scan" class="text-cyan" style="font-size: 0.9rem; font-weight: 600;">
        <i class="fas fa-qrcode"></i> Scan QR
    </a>
</div>

<div class="card mb-5">
    <div class="card-body" style="padding: 0 20px;">
        <?php if(empty($waitingList)): ?>
            <p class="text-muted" style="text-align: center; padding: 24px 0; margin: 0;">No one in the queue.</p>
        <?php else: ?>
            <?php $pos = 1; foreach($waitingList as $q): ?>
                <div style="display: flex; align-items: center; gap: 14px; padding: 14px 0; border-bottom: 1px solid var(--border-color);">
                    <div style="width: 32px; height: 32px; background: var(--bg-dark); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; flex-shrink: 0; color: var(--secondary-color);">
                        <?= $pos++ ?>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <strong style="display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= htmlspecialchars($q['customer_name']) ?></strong>
                        <span class="text-muted" style="font-size: 0.82rem;"><?= htmlspecialchars($q['service_name']) ?> &middot; <?= date('g:i A', strtotime($q['joined_at'])) ?></span>
                    </div>
                    <form action="<?= BASE_URL ?>/index.php?controller=qr&action=markNoShow" method="POST">
                        <input type="hidden" name="queue_id" value="<?= $q['id'] ?>">
                        <button type="submit" class="confirm-action" style="background: none; border: 1px solid rgba(255,69,58,0.3); color: var(--danger); padding: 5px 12px; border-radius: 8px; font-size: 0.78rem; cursor: pointer; white-space: nowrap;">
                            No Show
                        </button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>
