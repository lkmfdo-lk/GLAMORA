<?php require_once 'app/views/layouts/header.php'; ?>

<style>
    /* Hide header and footer navigation for auth pages */
    .main-header, .mobile-bottom-nav, .main-footer { display: none !important; }
    body { background-color: var(--bg-dark); justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
    .main-content { flex: none; width: 100%; max-width: 400px; padding: 0; margin-top: 20px; margin-bottom: 20px; }
</style>

<div style="text-align: center; margin-bottom: 30px;">
    <img src="<?= BASE_URL ?>/public/assets/img/logo.png?v=<?= time() ?>" alt="<?= APP_NAME ?>" style="height: 50px; margin: 0 auto 15px; display: block; object-fit: contain;">
    <h1 style="font-size: 1.8rem; font-weight: 700; letter-spacing: -1px;">Create Account</h1>
    <p class="text-muted" style="font-size: 0.95rem;">Join us to book your grooming sessions</p>
</div>

<?php if(!empty($error)): ?>
    <div class="alert alert-danger" style="background: rgba(255, 69, 58, 0.1); border: 1px solid rgba(255, 69, 58, 0.3); color: var(--danger); border-radius: 12px;">
        <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<form action="<?= BASE_URL ?>/index.php?controller=auth&action=register" method="POST" style="background: var(--primary-color); padding: 30px 20px; border-radius: 24px; box-shadow: 0 10px 40px rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.03);">
    
    <div class="form-group mb-3">
        <label style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Full Name</label>
        <div style="position: relative;">
            <i class="far fa-user" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
            <input type="text" name="full_name" class="form-control" required placeholder="John Doe" style="padding-left: 45px; background: rgba(0,0,0,0.2); border-color: transparent; border-radius: 16px;" value="<?= isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : '' ?>">
        </div>
    </div>

    <div class="form-group mb-3">
        <label style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Email Address</label>
        <div style="position: relative;">
            <i class="far fa-envelope" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
            <input type="email" name="email" class="form-control" required placeholder="name@example.com" style="padding-left: 45px; background: rgba(0,0,0,0.2); border-color: transparent; border-radius: 16px;" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
        </div>
    </div>

    <div class="form-group mb-3">
        <label style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Phone Number</label>
        <div style="position: relative;">
            <i class="fas fa-phone-alt" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
            <input type="tel" name="phone" class="form-control" required placeholder="0771234567" style="padding-left: 45px; background: rgba(0,0,0,0.2); border-color: transparent; border-radius: 16px;" value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '' ?>">
        </div>
    </div>

    <div class="form-group mb-3">
        <label style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Password</label>
        <div style="position: relative;">
            <i class="fas fa-lock" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
            <input type="password" name="password" class="form-control" required placeholder="Create a password" style="padding-left: 45px; background: rgba(0,0,0,0.2); border-color: transparent; border-radius: 16px;">
        </div>
    </div>

    <div class="form-group mb-4">
        <label style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Confirm Password</label>
        <div style="position: relative;">
            <i class="fas fa-lock" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
            <input type="password" name="confirm_password" class="form-control" required placeholder="Repeat your password" style="padding-left: 45px; background: rgba(0,0,0,0.2); border-color: transparent; border-radius: 16px;">
        </div>
    </div>
    
    <button type="submit" class="btn btn-primary btn-block" style="padding: 16px; font-size: 1.05rem; border-radius: 16px; box-shadow: 0 4px 15px rgba(69, 227, 211, 0.3);">
        Sign Up <i class="fas fa-user-plus" style="margin-left: 5px;"></i>
    </button>
</form>

<div style="text-align: center; margin-top: 25px;">
    <p class="text-muted">Already have an account? <a href="<?= BASE_URL ?>/index.php?controller=auth&action=login" class="text-cyan" style="font-weight: 600;">Sign in</a></p>
    <div style="margin-top: 15px;">
        <a href="<?= BASE_URL ?>/index.php?controller=owner&action=register" class="text-muted" style="font-size: 0.85rem; text-decoration: underline;">I am a Salon Owner</a>
    </div>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>
