    </main>
    
    <!-- Standard Desktop Footer -->
    <footer class="main-footer">
        <div class="container text-center">
            <p>&copy; <?= date('Y') ?> <?= APP_NAME ?>. All rights reserved.</p>
        </div>
    </footer>
    
    <!-- Mobile Bottom Navigation Bar (Floating Pill) -->
    <nav class="mobile-bottom-nav">
        <?php if(isset($_SESSION['user_id'])): ?>
            
            <?php if($_SESSION['user_role'] === 'customer'): ?>
                <a href="<?= BASE_URL ?>/index.php?controller=customer&action=dashboard" class="<?= ($_GET['action'] ?? '') === 'dashboard' ? 'active' : '' ?>"><i class="fas fa-home"></i></a>
                <a href="<?= BASE_URL ?>/index.php?controller=customer&action=search" class="<?= ($_GET['action'] ?? '') === 'search' ? 'active' : '' ?>"><i class="fas fa-search"></i></a>
                <a href="<?= BASE_URL ?>/index.php?controller=customer&action=appointments" class="<?= ($_GET['action'] ?? '') === 'appointments' ? 'active' : '' ?>"><i class="far fa-calendar-alt"></i></a>
                <a href="<?= BASE_URL ?>/index.php?controller=customer&action=profile" class="<?= ($_GET['action'] ?? '') === 'profile' ? 'active' : '' ?>"><i class="far fa-user"></i></a>
            <?php endif; ?>
            
            <?php if($_SESSION['user_role'] === 'owner'): ?>
                <a href="<?= BASE_URL ?>/index.php?controller=owner&action=dashboard" class="active"><i class="fas fa-chart-line"></i></a>
                <a href="<?= BASE_URL ?>/index.php?controller=owner&action=queue"><i class="fas fa-users"></i></a>
                <a href="<?= BASE_URL ?>/index.php?controller=service&action=list"><i class="fas fa-cut"></i></a>
                <a href="<?= BASE_URL ?>/index.php?controller=auth&action=logout"><i class="fas fa-sign-out-alt"></i></a>
            <?php endif; ?>
            
            <?php if($_SESSION['user_role'] === 'barber'): ?>
                <a href="<?= BASE_URL ?>/index.php?controller=barber&action=dashboard" class="active"><i class="fas fa-home"></i></a>
                <a href="<?= BASE_URL ?>/index.php?controller=qr&action=scan"><i class="fas fa-qrcode"></i></a>
                <a href="<?= BASE_URL ?>/index.php?controller=auth&action=logout"><i class="fas fa-sign-out-alt"></i></a>
            <?php endif; ?>
            
        <?php else: ?>
            <a href="<?= BASE_URL ?>/index.php?controller=auth&action=login" class="active"><i class="fas fa-sign-in-alt"></i></a>
            <a href="<?= BASE_URL ?>/index.php?controller=auth&action=register"><i class="fas fa-user-plus"></i></a>
        <?php endif; ?>
    </nav>
    
    <script src="<?= BASE_URL ?>/public/assets/js/main.js"></script>
    <script>
        // Check if we navigated to search from bottom nav
        document.addEventListener("DOMContentLoaded", function() {
            if(window.location.hash === '#search') {
                const searchInput = document.querySelector('.search-input');
                if(searchInput) {
                    searchInput.focus();
                    window.scrollTo(0, 0);
                }
            }
        });
    </script>
    <?php if(isset($extraJs)): ?>
        <?php foreach($extraJs as $jsFile): ?>
            <script src="<?= BASE_URL ?>/public/assets/<?= $jsFile ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
