<?php require_once 'app/views/layouts/header.php'; ?>

<style>
    /* Hide the standard header padding so the hero bleeds to the top */
    .main-content { padding-top: 0 !important; }
    .select-styled {
        appearance: none;
        background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12'%3E%3Cpath fill='%2345E3D3' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 15px center;
        padding-right: 40px;
        cursor: pointer;
    }
</style>

<?php
$coverImg = !empty($salon['image'])
    ? BASE_URL . '/' . $salon['image']
    : 'https://images.unsplash.com/photo-1560066984-138dadb4c035?auto=format&fit=crop&w=800&q=80';
?>

<!-- Full-bleed Hero -->
<div style="height: 260px; background-image: url('<?= $coverImg ?>'); background-size: cover; background-position: center; position: relative; margin: -20px -20px 0 -20px;">
    <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(18,18,18,0.95) 100%);"></div>

    <!-- Back button overlaid on hero -->
    <a href="<?= BASE_URL ?>/index.php?controller=customer&action=dashboard"
       style="position: absolute; top: 20px; left: 20px; background: rgba(0,0,0,0.5); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.15); color: white; padding: 8px 16px; border-radius: 50px; font-size: 0.9rem; text-decoration: none; display: flex; align-items: center; gap: 6px;">
        <i class="fas fa-arrow-left"></i> Back
    </a>

    <!-- Salon name at bottom of hero -->
    <div style="position: absolute; bottom: 20px; left: 20px; right: 20px;">
        <?php if(!empty($salon['logo'])): ?>
            <img src="<?= BASE_URL . '/' . $salon['logo'] ?>" style="width: 50px; height: 50px; border-radius: 12px; object-fit: cover; margin-bottom: 10px; border: 2px solid rgba(255,255,255,0.2);">
        <?php endif; ?>
        <h2 style="font-size: 1.8rem; font-weight: 700; margin-bottom: 4px; line-height: 1.1;"><?= htmlspecialchars($salon['name']) ?></h2>
        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
            <span class="text-muted" style="font-size: 0.9rem;"><i class="fas fa-map-marker-alt text-cyan" style="margin-right: 4px;"></i><?= htmlspecialchars($salon['city']) ?></span>
            <?php if(!empty($salon['map_link'])): ?>
                <a href="<?= htmlspecialchars($salon['map_link']) ?>" target="_blank" class="text-cyan" style="font-size: 0.85rem;"><i class="fas fa-directions"></i> Directions</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if(isset($_SESSION['error_msg'])): ?>
    <div class="alert alert-danger mt-3"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($_SESSION['error_msg']) ?></div>
    <?php unset($_SESSION['error_msg']); ?>
<?php endif; ?>

<!-- Quick Info Pills -->
<div style="display: flex; gap: 10px; margin: 20px 0; flex-wrap: wrap;">
    <div style="background: var(--primary-color); padding: 10px 16px; border-radius: 50px; display: flex; align-items: center; gap: 8px; font-size: 0.85rem;">
        <i class="far fa-clock text-cyan"></i>
        <span><?= date('g:i A', strtotime($salon['opening_time'])) ?> – <?= date('g:i A', strtotime($salon['closing_time'])) ?></span>
    </div>
    <a href="tel:<?= htmlspecialchars($salon['phone']) ?>" style="background: var(--primary-color); padding: 10px 16px; border-radius: 50px; display: flex; align-items: center; gap: 8px; font-size: 0.85rem; text-decoration: none; color: white;">
        <i class="fas fa-phone-alt text-cyan"></i>
        <span><?= htmlspecialchars($salon['phone']) ?></span>
    </a>
</div>

<!-- About -->
<?php if(!empty($salon['description'])): ?>
<div class="card mb-4">
    <div class="card-body">
        <h4 style="font-weight: 700; margin-bottom: 10px;">About</h4>
        <p class="text-muted" style="margin: 0; line-height: 1.6;"><?= nl2br(htmlspecialchars($salon['description'])) ?></p>
    </div>
</div>
<?php endif; ?>

<!-- Services List -->
<div class="card mb-4">
    <div class="card-body" style="padding: 20px 20px 0 20px;">
        <h4 style="font-weight: 700; margin-bottom: 16px;">Services</h4>
    </div>
    <?php if(empty($services)): ?>
        <p class="text-muted" style="padding: 0 20px 20px;">No services currently active.</p>
    <?php else: ?>
        <div style="display: flex; flex-direction: column;">
            <?php foreach($services as $i => $service): ?>
                <div style="padding: 14px 20px; <?= $i !== count($services)-1 ? 'border-bottom: 1px solid var(--border-color);' : 'padding-bottom: 20px;' ?> display: flex; justify-content: space-between; align-items: center; gap: 10px;">
                    <div>
                        <strong style="display: block; margin-bottom: 2px;"><?= htmlspecialchars($service['name']) ?></strong>
                        <span class="text-muted" style="font-size: 0.82rem;"><?= htmlspecialchars($service['description']) ?></span>
                    </div>
                    <div style="text-align: right; flex-shrink: 0;">
                        <strong style="color: var(--secondary-color); display: block;">Rs. <?= number_format($service['price']) ?></strong>
                        <span class="text-muted" style="font-size: 0.78rem;"><i class="far fa-clock"></i> <?= $service['duration_minutes'] ?> min</span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Booking Form -->
<div class="card mb-5" style="border: 1px solid rgba(69,227,211,0.3);">
    <div class="card-body">
        <h4 style="font-weight: 700; margin-bottom: 5px;"><i class="fas fa-bolt text-cyan" style="margin-right: 6px;"></i>Book Appointment</h4>
        <p class="text-muted" style="font-size: 0.88rem; margin-bottom: 20px;">Select a service and barber to join the queue.</p>

        <form action="<?= BASE_URL ?>/index.php?controller=queue&action=join" method="POST">
            <input type="hidden" name="salon_id" value="<?= $salon['id'] ?>">

            <div class="form-group" style="margin-bottom: 14px;">
                <label style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Service</label>
                <select name="service_id" class="form-control select-styled" required>
                    <option value="">-- Choose Service --</option>
                    <?php foreach($services as $service): ?>
                        <option value="<?= $service['id'] ?>"><?= htmlspecialchars($service['name']) ?> — Rs. <?= number_format($service['price']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Barber</label>
                <select name="barber_id" class="form-control select-styled" required>
                    <option value="any">Any Available Barber</option>
                    <?php foreach($barbers as $barber): ?>
                        <option value="<?= $barber['id'] ?>"><?= htmlspecialchars($barber['full_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Preferred Time</label>
                <input type="time" name="scheduled_time" class="form-control" value="<?= date('H:i') ?>">
            </div>

            <button type="submit" class="btn btn-primary btn-block" style="padding: 15px; font-size: 1rem; border-radius: 14px; font-weight: 700; letter-spacing: 0.3px; box-shadow: 0 4px 20px rgba(69,227,211,0.25);">
                <i class="fas fa-ticket-alt" style="margin-right: 8px;"></i> Book Now
            </button>
        </form>
    </div>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>
