<?php require_once 'app/views/layouts/header.php'; ?>

<style>
    /* Hide header and footer navigation for auth pages to make them look like app splash screens */
    .main-header, .mobile-bottom-nav, .main-footer { display: none !important; }
    body { background-color: var(--bg-dark); justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
    .main-content { flex: none; width: 100%; max-width: 400px; padding: 0; }
</style>

<div style="text-align: center; margin-bottom: 40px; margin-top: 20px;">
    <img src="<?= BASE_URL ?>/public/assets/img/logo.png?v=<?= time() ?>" alt="<?= APP_NAME ?>" style="height: 60px; margin: 0 auto 20px; display: block; object-fit: contain;">
    <h1 style="font-size: 2rem; font-weight: 700; letter-spacing: -1px;">Welcome Back</h1>
    <p class="text-muted" style="font-size: 0.95rem;">Sign in to book your next appointment</p>
</div>

<?php if(!empty($_SESSION['flash_success'])): ?>
    <div class="alert alert-success" style="background: rgba(50, 215, 75, 0.1); border: 1px solid rgba(50, 215, 75, 0.3); color: var(--success); border-radius: 12px; margin-bottom: 15px;">
        <i class="fas fa-check-circle"></i> <?= htmlspecialchars($_SESSION['flash_success']) ?>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?php if(!empty($error)): ?>
    <div class="alert alert-danger" style="background: rgba(255, 69, 58, 0.1); border: 1px solid rgba(255, 69, 58, 0.3); color: var(--danger); border-radius: 12px;">
        <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<form action="<?= BASE_URL ?>/index.php?controller=auth&action=login" method="POST" style="background: var(--primary-color); padding: 30px 20px; border-radius: 24px; box-shadow: 0 10px 40px rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.03);">
    
    <div class="form-group mb-4">
        <label style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px;">Email Address</label>
        <div style="position: relative;">
            <i class="far fa-envelope" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
            <input type="email" name="email" class="form-control" required placeholder="name@example.com" style="padding-left: 45px; background: rgba(0,0,0,0.2); border-color: transparent; border-radius: 16px;">
        </div>
    </div>
    
    <div class="form-group mb-4">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
            <label style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0;">Password</label>
            <a href="#" class="text-cyan" style="font-size: 0.8rem; font-weight: 500;">Forgot?</a>
        </div>
        <div style="position: relative;">
            <i class="fas fa-lock" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
            <input type="password" name="password" class="form-control" required placeholder="••••••••" style="padding-left: 45px; background: rgba(0,0,0,0.2); border-color: transparent; border-radius: 16px;">
        </div>
    </div>
    
    <button type="submit" class="btn btn-primary btn-block" style="padding: 16px; font-size: 1.05rem; border-radius: 16px; margin-top: 10px; box-shadow: 0 4px 15px rgba(69, 227, 211, 0.3);">
        Sign In <i class="fas fa-arrow-right" style="margin-left: 5px;"></i>
    </button>
</form>

<div style="text-align: center; margin-top: 30px;">
    <p class="text-muted">Don't have an account? <br><a href="<?= BASE_URL ?>/index.php?controller=auth&action=register" class="text-cyan" style="font-weight: 600; display: inline-block; margin-top: 8px;">Create new account</a></p>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>
