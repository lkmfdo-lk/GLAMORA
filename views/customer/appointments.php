<?php require_once 'app/views/layouts/header.php'; ?>

<div class="section-header mt-2 mb-4">
    <h3 class="section-title">My Appointments</h3>
</div>

<?php if (empty($upcoming)): ?>
    <div class="card mb-5">
        <div class="card-body" style="text-align: center; padding: 40px 20px;">
            <div style="width: 80px; height: 80px; background: rgba(69, 227, 211, 0.1); color: var(--secondary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 20px auto;">
                <i class="far fa-calendar-alt"></i>
            </div>
            <h4 style="margin-bottom: 10px;">No Upcoming Appointments</h4>
            <p class="text-muted" style="margin-bottom: 25px;">You don't have any appointments scheduled yet. Browse salons to book a service or join a live queue.</p>
            
            <a href="<?= BASE_URL ?>/index.php?controller=customer&action=dashboard" class="btn btn-primary" style="padding: 12px 30px; border-radius: 50px;">Browse Salons</a>
        </div>
    </div>
<?php else: ?>
    <?php foreach($upcoming as $item): ?>
        <a href="<?= BASE_URL ?>/index.php?controller=customer&action=queueDetails&id=<?= $item['id'] ?>" style="text-decoration: none; color: inherit; display: block;">
            <div class="card card-cyan mb-4" style="border-radius: 20px; overflow: hidden; position: relative;">
                <div style="padding: 24px;">
                    <p class="text-muted" style="font-size: 0.9rem; font-weight: 500; margin-bottom: 5px;"><?= htmlspecialchars($item['salon_name']) ?></p>
                    <h2 style="font-size: 1.5rem; font-weight: 700; line-height: 1.1; margin-bottom: 10px;"><?= htmlspecialchars($item['service_name']) ?></h2>
                    <p style="font-size: 0.9rem; margin-bottom: 0; font-weight: 500;">
                        Status: <?= ucfirst(str_replace('_', ' ', $item['status'])) ?>
                    </p>
                </div>
            </div>
        </a>
    <?php endforeach; ?>
<?php endif; ?>

<div class="section-header mt-5">
    <h3 class="section-title">Past History</h3>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($past)): ?>
            <p class="text-muted text-center my-3">No past history found.</p>
        <?php else: ?>
            <?php foreach($past as $index => $item): ?>
                <div style="display: flex; align-items: center; gap: 15px; padding: <?= $index === count($past) - 1 ? '15px 0 5px 0' : '10px 0' ?>; <?= $index !== count($past) - 1 ? 'border-bottom: 1px solid var(--border-color);' : '' ?>">
                    <div style="width: 50px; height: 50px; border-radius: 12px; background: var(--primary-color); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: var(--text-muted);">
                        <i class="fas fa-store"></i>
                    </div>
                    <div style="flex: 1;">
                        <strong style="display: block;"><?= htmlspecialchars($item['salon_name']) ?></strong>
                        <span class="text-muted" style="font-size: 0.85rem;"><?= htmlspecialchars($item['service_name']) ?> • <?= ucfirst(str_replace('_', ' ', $item['status'])) ?></span>
                    </div>
                    <div class="text-muted" style="font-size: 0.85rem; text-align: right;">
                        <?= date('M d', strtotime($item['joined_at'])) ?><br><?= date('Y', strtotime($item['joined_at'])) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>
