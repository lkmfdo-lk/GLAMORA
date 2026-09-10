<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= APP_NAME ?></title>
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/style.css?v=<?= time() ?>">
    <?php if(isset($extraCss)): ?>
        <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/<?= $extraCss ?>.css?v=<?= time() ?>">
    <?php endif; ?>
</head>
<body>
    <header class="main-header">
        <div class="container nav-container">
            
            <?php if(isset($_SESSION['user_id'])): ?>
                <!-- User Greeting matching the dark UI design -->
                <div class="user-greeting">
                    <?php 
                    $firstName = htmlspecialchars(explode(' ', $_SESSION['user_full_name'] ?? 'User')[0]);
                    $avatarColor = $_SESSION['user_role'] === 'customer' ? '45E3D3' : 'FF9F0A';
                    ?>
                    <div class="avatar-circle" style="background-image: url('https://ui-avatars.com/api/?name=<?= urlencode($firstName) ?>&background=<?= $avatarColor ?>&color=000');"></div>
                    <div class="greeting-text">
                        <small>Hello <?= $firstName ?></small>
                        <strong>Good morning!</strong>
                    </div>
                </div>
            <?php else: ?>
                <a href="<?= BASE_URL ?>" class="logo">
                    <img src="<?= BASE_URL ?>/public/assets/img/logo.png?v=<?= time() ?>" alt="<?= APP_NAME ?>" style="height: 35px; object-fit: contain;">
                </a>
            <?php endif; ?>
            
            <!-- Desktop Navigation -->
            <nav class="desktop-nav">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <?php if($_SESSION['user_role'] === 'customer'): ?>
                        <a href="<?= BASE_URL ?>/index.php?controller=customer&action=dashboard">Home</a>
                    <?php endif; ?>
                    
                    <?php if($_SESSION['user_role'] === 'owner'): ?>
                        <a href="<?= BASE_URL ?>/index.php?controller=owner&action=dashboard">Dashboard</a>
                        <a href="<?= BASE_URL ?>/index.php?controller=owner&action=queue">Queue</a>
                        <a href="<?= BASE_URL ?>/index.php?controller=service&action=list">Services</a>
                        <a href="<?= BASE_URL ?>/index.php?controller=barber&action=list">Barbers</a>
                    <?php endif; ?>
                    
                    <?php if($_SESSION['user_role'] === 'barber'): ?>
                        <a href="<?= BASE_URL ?>/index.php?controller=barber&action=dashboard">Dashboard</a>
                        <a href="<?= BASE_URL ?>/index.php?controller=qr&action=scan">Scan QR</a>
                    <?php endif; ?>
                    
                    <a href="<?= BASE_URL ?>/index.php?controller=auth&action=logout" style="margin-left: 20px;"><i class="fas fa-sign-out-alt"></i></a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/index.php?controller=auth&action=login">Login</a>
                    <a href="<?= BASE_URL ?>/index.php?controller=auth&action=register" class="btn btn-sm btn-primary">Register</a>
                <?php endif; ?>
            </nav>
            
            <!-- Mobile Notification Icon (Visual) -->
            <?php if(isset($_SESSION['user_id'])): ?>
                <div class="mobile-only" style="position: relative; cursor: pointer;" onclick="alert('You have no new notifications.')">
                    <i class="far fa-bell" style="font-size: 1.2rem; color: var(--text-main);"></i>
                    <span style="position: absolute; top: -2px; right: -2px; width: 8px; height: 8px; background: var(--danger); border-radius: 50%;"></span>
                </div>
            <?php endif; ?>
            
        </div>
    </header>
    <main class="container main-content">
        
