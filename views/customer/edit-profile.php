<?php require_once 'app/views/layouts/header.php'; ?>

<div class="section-header mt-2 mb-4 d-flex align-items-center">
    <a href="<?= BASE_URL ?>/index.php?controller=customer&action=profile" class="text-cyan" style="font-size: 1.2rem; margin-right: 15px;"><i class="fas fa-arrow-left"></i></a>
    <h3 class="section-title mb-0">Edit Profile</h3>
</div>

<?php if(!empty($error)): ?>
    <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if(!empty($success)): ?>
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div class="card" style="padding: 24px; border-radius: 20px;">
    <form action="<?= BASE_URL ?>/index.php?controller=customer&action=editProfile" method="POST">
        
        <div class="form-group" style="margin-bottom: 20px;">
            <label style="color: var(--text-muted); font-size: 0.9rem;">Full Name</label>
            <input type="text" name="full_name" class="form-control" style="background: var(--primary-color); border: none; color: white;" value="<?= htmlspecialchars($user['full_name'] ?? '') ?>" required>
        </div>
        
        <div class="form-group" style="margin-bottom: 20px;">
            <label style="color: var(--text-muted); font-size: 0.9rem;">Email Address</label>
            <input type="email" name="email" class="form-control" style="background: var(--primary-color); border: none; color: white;" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
        </div>
        
        <div class="form-group" style="margin-bottom: 30px;">
            <label style="color: var(--text-muted); font-size: 0.9rem;">Phone Number</label>
            <input type="tel" name="phone" class="form-control" style="background: var(--primary-color); border: none; color: white;" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required>
        </div>
        
        <button type="submit" class="btn btn-primary btn-block" style="padding: 14px; border-radius: 12px; font-weight: 600; font-size: 1rem;">Save Changes</button>
        
    </form>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>
