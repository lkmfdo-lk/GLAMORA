<?php require_once 'app/views/layouts/header.php'; ?>

<div style="text-align: center; margin-top: 10px; margin-bottom: 30px;">
    <?php 
    $firstName = htmlspecialchars(explode(' ', $_SESSION['user_full_name'] ?? 'User')[0]);
    $avatarColor = $_SESSION['user_role'] === 'customer' ? '45E3D3' : 'FF9F0A';
    ?>
    <div style="width: 90px; height: 90px; border-radius: 50%; margin: 0 auto 15px auto; background-image: url('https://ui-avatars.com/api/?name=<?= urlencode($firstName) ?>&background=<?= $avatarColor ?>&color=000&size=128'); background-size: cover; border: 3px solid var(--primary-color);"></div>
    <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 2px;"><?= htmlspecialchars($_SESSION['user_full_name'] ?? 'User') ?></h2>
    <p class="text-muted" style="font-size: 0.9rem; margin-bottom: 15px;"><?= htmlspecialchars($_SESSION['user_email'] ?? '') ?></p>
    
    <a href="<?= BASE_URL ?>/index.php?controller=customer&action=editProfile" class="btn btn-primary" style="padding: 8px 24px; border-radius: 50px; font-size: 0.9rem; font-weight: 600;">Edit Profile</a>
</div>

<div class="card mb-4" style="background: var(--primary-color); border: none; border-radius: 20px;">
    <div style="padding: 20px 20px 10px 20px;">
        <h3 style="font-size: 1rem; color: var(--text-main); font-weight: 600; margin-bottom: 5px;">Profile Setting</h3>
    </div>
    <div class="card-body" style="padding: 0 10px 10px 10px;">
        <a href="#" style="display: flex; align-items: center; padding: 15px 10px; color: var(--text-main); border-bottom: 1px solid rgba(255,255,255,0.05); text-decoration: none;">
            <div style="width: 32px; color: var(--text-muted);"><i class="far fa-user"></i></div>
            <div style="flex: 1; font-weight: 500;">Friend & Social</div>
            <div style="color: var(--text-muted);"><i class="fas fa-chevron-right" style="font-size: 0.8rem;"></i></div>
        </a>
        <a href="#" style="display: flex; align-items: center; padding: 15px 10px; color: var(--text-main); border-bottom: 1px solid rgba(255,255,255,0.05); text-decoration: none;">
            <div style="width: 32px; color: var(--text-muted);"><i class="far fa-comment-alt"></i></div>
            <div style="flex: 1; font-weight: 500;">Feedback</div>
            <div style="color: var(--text-muted);"><i class="fas fa-chevron-right" style="font-size: 0.8rem;"></i></div>
        </a>
        <a href="#" style="display: flex; align-items: center; padding: 15px 10px; color: var(--text-main); text-decoration: none;">
            <div style="width: 32px; color: var(--text-muted);"><i class="fas fa-gift"></i></div>
            <div style="flex: 1; font-weight: 500;">Gift Card</div>
            <div style="color: var(--text-muted);"><i class="fas fa-chevron-right" style="font-size: 0.8rem;"></i></div>
        </a>
    </div>
</div>

<div class="card" style="background: var(--primary-color); border: none; border-radius: 20px; margin-bottom: 80px;">
    <div style="padding: 20px 20px 10px 20px;">
        <h3 style="font-size: 1rem; color: var(--text-main); font-weight: 600; margin-bottom: 5px;">Others Setting</h3>
    </div>
    <div class="card-body" style="padding: 0 10px 10px 10px;">
        <a href="#" style="display: flex; align-items: center; padding: 15px 10px; color: var(--text-main); border-bottom: 1px solid rgba(255,255,255,0.05); text-decoration: none;">
            <div style="width: 32px; color: var(--text-muted);"><i class="fas fa-cog"></i></div>
            <div style="flex: 1; font-weight: 500;">Account & Setting</div>
            <div style="color: var(--text-muted);"><i class="fas fa-chevron-right" style="font-size: 0.8rem;"></i></div>
        </a>
        <a href="<?= BASE_URL ?>/index.php?controller=auth&action=logout" style="display: flex; align-items: center; padding: 15px 10px; color: var(--danger); text-decoration: none;">
            <div style="width: 32px; color: var(--danger);"><i class="fas fa-sign-out-alt"></i></div>
            <div style="flex: 1; font-weight: 500;">Logout</div>
            <div style="color: var(--text-muted);"><i class="fas fa-chevron-right" style="font-size: 0.8rem;"></i></div>
        </a>
    </div>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>
