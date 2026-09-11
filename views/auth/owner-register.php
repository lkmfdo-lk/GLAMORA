<?php require_once 'app/views/layouts/header.php'; ?>

<style>
    /* Hide header and footer navigation for auth pages */
    .main-header, .mobile-bottom-nav, .main-footer { display: none !important; }
    body { background-color: var(--bg-dark); display: flex; flex-direction: column; align-items: center; min-height: 100vh; padding: 20px; }
    .main-content { flex: none; width: 100%; max-width: 800px; padding: 0; margin-top: 20px; margin-bottom: 20px; }
    .form-control { background: rgba(0,0,0,0.2) !important; border-color: transparent !important; color: white !important; border-radius: 12px !important; }
    .form-group label { font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; color: var(--text-muted); }
    h3 { font-size: 1.1rem; color: var(--secondary-color); margin-bottom: 15px; }
    hr { border-color: rgba(255,255,255,0.05); }
</style>

<div style="text-align: center; margin-bottom: 30px;">
    <img src="<?= BASE_URL ?>/public/assets/img/logo.png?v=<?= time() ?>" alt="<?= APP_NAME ?>" style="height: 50px; margin: 0 auto 15px; display: block; object-fit: contain;">
    <h1 style="font-size: 1.8rem; font-weight: 700; letter-spacing: -1px;">Register Your Salon</h1>
    <p class="text-muted" style="font-size: 0.95rem;">Partner with us to manage your queue efficiently</p>
</div>

<?php if(!empty($error)): ?>
    <div class="alert alert-danger" style="background: rgba(255, 69, 58, 0.1); border: 1px solid rgba(255, 69, 58, 0.3); color: var(--danger); border-radius: 12px; margin-bottom: 20px;">
        <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<?php if(!empty($success)): ?>
    <div class="alert alert-success" style="background: rgba(50, 215, 75, 0.1); border: 1px solid rgba(50, 215, 75, 0.3); color: var(--success); border-radius: 12px; margin-bottom: 20px;">
        <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?>
    </div>
<?php endif; ?>

<form action="<?= BASE_URL ?>/index.php?controller=owner&action=register" method="POST" enctype="multipart/form-data" style="background: var(--primary-color); padding: 30px; border-radius: 24px; box-shadow: 0 10px 40px rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.03);">
    
    <div style="display: flex; gap: 30px; flex-wrap: wrap;">
        
        <!-- Owner Details Column -->
        <div style="flex: 1; min-width: 300px;">
            <h3>Owner Details</h3>
            <hr class="mb-3">
            
            <div class="form-group mb-3">
                <label>Full Name *</label>
                <div style="position: relative;">
                    <i class="far fa-user" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                    <input type="text" name="full_name" required class="form-control" style="padding-left: 45px;" value="<?= isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : '' ?>">
                </div>
            </div>
            
            <div class="form-group mb-3">
                <label>Email Address *</label>
                <div style="position: relative;">
                    <i class="far fa-envelope" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                    <input type="email" name="email" required class="form-control" style="padding-left: 45px;" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                </div>
            </div>
            
            <div class="form-group mb-3">
                <label>Phone Number</label>
                <div style="position: relative;">
                    <i class="fas fa-phone-alt" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                    <input type="text" name="phone" class="form-control" style="padding-left: 45px;" value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '' ?>">
                </div>
            </div>
            
            <div class="form-group mb-4">
                <label>Password *</label>
                <div style="position: relative;">
                    <i class="fas fa-lock" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                    <input type="password" name="password" required class="form-control" style="padding-left: 45px;">
                </div>
            </div>
        </div>
        
        <!-- Salon Details Column -->
        <div style="flex: 1; min-width: 300px;">
            <h3>Salon Details</h3>
            <hr class="mb-3">
            
            <div class="form-group mb-3">
                <label>Salon Name *</label>
                <div style="position: relative;">
                    <i class="fas fa-store" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                    <input type="text" name="salon_name" required class="form-control" style="padding-left: 45px;" value="<?= isset($_POST['salon_name']) ? htmlspecialchars($_POST['salon_name']) : '' ?>">
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <div class="form-group mb-3" style="flex: 1;">
                    <label>City *</label>
                    <input type="text" name="city" required class="form-control" value="<?= isset($_POST['city']) ? htmlspecialchars($_POST['city']) : '' ?>">
                </div>
                <div class="form-group mb-3" style="flex: 1;">
                    <label>Salon Phone</label>
                    <input type="text" name="salon_phone" class="form-control" value="<?= isset($_POST['salon_phone']) ? htmlspecialchars($_POST['salon_phone']) : '' ?>">
                </div>
            </div>
            
            <div class="form-group mb-3">
                <label>Address</label>
                <textarea name="address" class="form-control" rows="2"><?= isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '' ?></textarea>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <div class="form-group mb-3" style="flex: 1;">
                    <label>Opening Time</label>
                    <input type="time" name="opening_time" required class="form-control" value="<?= isset($_POST['opening_time']) ? htmlspecialchars($_POST['opening_time']) : '08:00' ?>">
                </div>
                <div class="form-group mb-3" style="flex: 1;">
                    <label>Closing Time</label>
                    <input type="time" name="closing_time" required class="form-control" value="<?= isset($_POST['closing_time']) ? htmlspecialchars($_POST['closing_time']) : '20:00' ?>">
                </div>
            </div>
            
            <div class="form-group mb-3">
                <label>Google Maps Link <span style="color: var(--text-muted); font-size: 0.75rem;">(optional)</span></label>
                <div style="position: relative;">
                    <i class="fas fa-map-marker-alt" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                    <input type="url" name="map_link" class="form-control" style="padding-left: 45px;" placeholder="https://maps.google.com/..." value="<?= isset($_POST['map_link']) ? htmlspecialchars($_POST['map_link']) : '' ?>">
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <div class="form-group mb-3" style="flex: 1;">
                    <label>Salon Logo <span style="color: var(--text-muted); font-size: 0.75rem;">(optional)</span></label>
                    <input type="file" name="logo" accept="image/*" class="form-control" style="padding: 10px; cursor: pointer;">
                </div>
                <div class="form-group mb-3" style="flex: 1;">
                    <label>Salon Cover Image <span style="color: var(--text-muted); font-size: 0.75rem;">(optional)</span></label>
                    <input type="file" name="image" accept="image/*" class="form-control" style="padding: 10px; cursor: pointer;">
                </div>
            </div>
        </div>
    </div>
    
    <button type="submit" class="btn btn-primary btn-block mt-4" style="padding: 16px; font-size: 1.05rem; border-radius: 16px; box-shadow: 0 4px 15px rgba(69, 227, 211, 0.3);">
        Register Business <i class="fas fa-arrow-right" style="margin-left: 5px;"></i>
    </button>
</form>

<div style="text-align: center; margin-top: 25px;">
    <p class="text-muted">Already have an account? <a href="<?= BASE_URL ?>/index.php?controller=auth&action=login" class="text-cyan" style="font-weight: 600;">Sign in here</a></p>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>
