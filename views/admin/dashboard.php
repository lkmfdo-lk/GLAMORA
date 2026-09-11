<?php 
$extraCss = 'dashboard';
require_once 'app/views/layouts/header.php'; 
?>

<div class="dashboard-header">
    <h2>Admin Dashboard</h2>
</div>

<div class="stats-container" style="display: flex; gap: 20px; margin-bottom: 30px;">
    <div class="card" style="flex: 1; text-align: center; border-top: 4px solid var(--primary-color);">
        <div class="card-body">
            <h3><?= $stats['total_salons'] ?></h3>
            <p class="text-muted">Total Salons</p>
        </div>
    </div>
    <div class="card" style="flex: 1; text-align: center; border-top: 4px solid var(--warning);">
        <div class="card-body">
            <h3><?= $stats['pending_salons'] ?></h3>
            <p class="text-muted">Pending Approval</p>
        </div>
    </div>
    <div class="card" style="flex: 1; text-align: center; border-top: 4px solid var(--success);">
        <div class="card-body">
            <h3><?= $stats['approved_salons'] ?></h3>
            <p class="text-muted">Approved Salons</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        Pending Salon Approvals
    </div>
    <div class="card-body">
        <?php if(empty($pendingSalons)): ?>
            <p>No pending salons to review.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Salon Name</th>
                        <th>Owner</th>
                        <th>City</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($pendingSalons as $salon): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($salon['name']) ?></strong><br>
                                <small class="text-muted"><?= htmlspecialchars($salon['address']) ?></small>
                            </td>
                            <td>
                                <?= htmlspecialchars($salon['owner_name']) ?><br>
                                <small class="text-muted"><?= htmlspecialchars($salon['owner_email']) ?></small>
                            </td>
                            <td><?= htmlspecialchars($salon['city']) ?></td>
                            <td><?= htmlspecialchars($salon['phone']) ?></td>
                            <td>
                                <div style="display: flex; gap: 5px;">
                                    <form action="<?= BASE_URL ?>/index.php?controller=admin&action=approveSalon" method="POST">
                                        <input type="hidden" name="salon_id" value="<?= $salon['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-success confirm-action">Approve</button>
                                    </form>
                                    
                                    <button class="btn btn-sm btn-danger" onclick="document.getElementById('reject-form-<?= $salon['id'] ?>').style.display='block'">Reject</button>
                                </div>
                                
                                <div id="reject-form-<?= $salon['id'] ?>" style="display: none; margin-top: 10px; background: #f8f9fa; padding: 10px; border-radius: 4px;">
                                    <form action="<?= BASE_URL ?>/index.php?controller=admin&action=rejectSalon" method="POST">
                                        <input type="hidden" name="salon_id" value="<?= $salon['id'] ?>">
                                        <div class="form-group mb-2">
                                            <input type="text" name="rejection_reason" class="form-control form-control-sm" placeholder="Reason for rejection" required>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-danger">Confirm Reject</button>
                                        <button type="button" class="btn btn-sm btn-outline" onclick="document.getElementById('reject-form-<?= $salon['id'] ?>').style.display='none'">Cancel</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>
