<?php require_once 'app/views/layouts/header.php'; ?>

<style>
    #scanner-box {
        width: 100%;
        border-radius: 16px;
        overflow: hidden;
        background: #000;
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    #reader { width: 100%; }
    .corner {
        position: absolute;
        width: 28px; height: 28px;
        border-color: var(--secondary-color);
        border-style: solid;
        opacity: 0.9;
    }
    .corner.tl { top: 14px; left: 14px; border-width: 3px 0 0 3px; border-radius: 4px 0 0 0; }
    .corner.tr { top: 14px; right: 14px; border-width: 3px 3px 0 0; border-radius: 0 4px 0 0; }
    .corner.bl { bottom: 14px; left: 14px; border-width: 0 0 3px 3px; border-radius: 0 0 0 4px; }
    .corner.br { bottom: 14px; right: 14px; border-width: 0 3px 3px 0; border-radius: 0 0 4px 0; }
</style>

<!-- Header -->
<div style="display: flex; align-items: center; gap: 14px; margin: 8px 0 24px;">
    <a href="<?= BASE_URL ?>/index.php?controller=barber&action=dashboard"
       style="background: var(--primary-color); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; color: white; flex-shrink: 0;">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div>
        <h3 style="margin: 0; font-weight: 700;">Scan Customer QR</h3>
        <p class="text-muted" style="margin: 0; font-size: 0.82rem;">Point the camera at the customer's ticket</p>
    </div>
</div>

<?php if(!empty($error)): ?>
    <div class="alert alert-danger" style="border-radius: 12px; margin-bottom: 20px;">
        <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<!-- Camera Scanner -->
<div class="card mb-4">
    <div class="card-body" style="padding: 20px;">

        <!-- Start Camera Button (shown first) -->
        <div id="start-screen" style="text-align: center; padding: 30px 10px;">
            <div style="width: 80px; height: 80px; background: rgba(69,227,211,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 2rem; color: var(--secondary-color);">
                <i class="fas fa-camera"></i>
            </div>
            <h4 style="font-weight: 700; margin-bottom: 8px;">Camera Scanner</h4>
            <p class="text-muted" style="font-size: 0.88rem; margin-bottom: 24px;">Tap the button below to allow camera access and scan the customer's QR code.</p>
            <button id="start-btn" class="btn btn-primary" style="padding: 14px 32px; border-radius: 50px; font-weight: 700; font-size: 1rem;">
                <i class="fas fa-camera" style="margin-right: 8px;"></i> Start Camera
            </button>
        </div>

        <!-- Scanner (hidden until started) -->
        <div id="scanner-screen" style="display: none;">
            <div id="scanner-box">
                <div id="reader"></div>
                <div class="corner tl"></div>
                <div class="corner tr"></div>
                <div class="corner bl"></div>
                <div class="corner br"></div>
            </div>
            <p class="text-muted" style="text-align: center; font-size: 0.82rem; margin-top: 12px;">
                <i class="fas fa-info-circle"></i> Align the QR code within the frame
            </p>
            <button id="stop-btn" class="btn btn-outline btn-block" style="margin-top: 8px; border-radius: 12px; padding: 12px;">
                <i class="fas fa-times" style="margin-right: 6px;"></i> Stop Camera
            </button>
        </div>

        <!-- Error state -->
        <div id="camera-error" style="display: none; text-align: center; padding: 20px 0;">
            <i class="fas fa-video-slash" style="font-size: 2rem; color: var(--danger); margin-bottom: 12px; display: block;"></i>
            <p style="font-weight: 600; margin-bottom: 6px;">Camera Not Available</p>
            <p class="text-muted" style="font-size: 0.85rem;">Please use the manual entry below instead.</p>
        </div>

        <form id="qr-form" action="<?= BASE_URL ?>/index.php?controller=qr&action=scan" method="POST" style="display:none;">
            <input type="hidden" name="qr_token" id="qr_token_input">
        </form>
    </div>
</div>

<!-- Manual Entry -->
<div class="card mb-5">
    <div class="card-body">
        <h4 style="font-weight: 700; margin-bottom: 6px;">Enter Code Manually</h4>
        <p class="text-muted" style="font-size: 0.85rem; margin-bottom: 16px;">If camera isn't working, type the token from the customer's ticket.</p>
        <form action="<?= BASE_URL ?>/index.php?controller=qr&action=scan" method="POST">
            <div class="form-group" style="margin-bottom: 14px;">
                <input type="text" name="qr_token" class="form-control"
                       placeholder="GLAM-Q-XXXXXXXX"
                       style="font-family: monospace; font-size: 1rem; letter-spacing: 2px; text-transform: uppercase; border-radius: 12px; text-align: center;"
                       required>
            </div>
            <button type="submit" class="btn btn-primary btn-block" style="padding: 14px; border-radius: 12px; font-weight: 700;">
                <i class="fas fa-play-circle" style="margin-right: 8px;"></i> Start Service
            </button>
        </form>
    </div>
</div>

<script src="<?= BASE_URL ?>/public/assets/vendor/html5-qrcode.min.js"></script>
<script>
let html5QrCode = null;

document.getElementById('start-btn').addEventListener('click', function() {
    document.getElementById('start-screen').style.display = 'none';
    document.getElementById('scanner-screen').style.display = 'block';

    html5QrCode = new Html5Qrcode("reader");

    const config = { fps: 10, qrbox: { width: 220, height: 220 } };

    html5QrCode.start(
        { facingMode: "environment" },
        config,
        function(decodedText) {
            html5QrCode.stop().then(() => {
                document.getElementById('qr_token_input').value = decodedText;
                document.getElementById('qr-form').submit();
            });
        }
    ).catch(function(err) {
        document.getElementById('scanner-screen').style.display = 'none';
        document.getElementById('camera-error').style.display = 'block';
        console.error("Camera error:", err);
    });
});

document.getElementById('stop-btn').addEventListener('click', function() {
    if (html5QrCode) {
        html5QrCode.stop().catch(() => {});
    }
    document.getElementById('scanner-screen').style.display = 'none';
    document.getElementById('start-screen').style.display = 'block';
});
</script>

<?php require_once 'app/views/layouts/footer.php'; ?>
