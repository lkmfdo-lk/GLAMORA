<?php require_once 'app/views/layouts/header.php'; ?>

<div class="section-header mt-2 mb-3">
    <h3 class="section-title">Find a Salon</h3>
</div>

<!-- Search Form -->
<form action="<?= BASE_URL ?>/index.php" method="GET" style="margin-bottom: 20px;">
    <input type="hidden" name="controller" value="customer">
    <input type="hidden" name="action" value="search">
    <div style="display: flex; gap: 10px;">
        <div style="position: relative; flex: 1;">
            <i class="fas fa-search" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
            <input type="text" name="q" id="search-input" class="form-control search-input" 
                   placeholder="Salon name or city..." 
                   value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>"
                   style="padding-left: 45px; background: var(--primary-color); border: none; border-radius: 50px; color: white;" autofocus>
        </div>
        <button type="submit" style="background: var(--secondary-color); min-width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: none; cursor: pointer;">
            <i class="fas fa-arrow-right" style="color: #000;"></i>
        </button>
    </div>
</form>

<?php if(isset($_GET['q']) && !empty(trim($_GET['q']))): ?>
    <!-- Search Results -->
    <p class="text-muted" style="font-size: 0.88rem; margin-bottom: 16px;">
        Results for "<strong style="color: white;"><?= htmlspecialchars($_GET['q']) ?></strong>"
    </p>
    <?php if(empty($salons)): ?>
        <div class="card" style="text-align: center; padding: 40px 20px;">
            <i class="fas fa-store-slash" style="font-size: 2.5rem; color: var(--text-muted); margin-bottom: 15px; display: block;"></i>
            <h4>No salons found</h4>
            <p class="text-muted">Try searching by city or salon name.</p>
        </div>
    <?php else: ?>
        <?php foreach($salons as $salon): ?>
            <?php
            $img = !empty($salon['image']) ? BASE_URL . '/' . $salon['image']
                : 'https://images.unsplash.com/photo-1560066984-138dadb4c035?auto=format&fit=crop&w=400&q=80';
            ?>
            <a href="<?= BASE_URL ?>/index.php?controller=customer&action=salonDetails&id=<?= $salon['id'] ?>" style="text-decoration: none; color: inherit; display: block; margin-bottom: 14px;">
                <div class="card" style="display: flex; flex-direction: row; overflow: hidden; border-radius: 16px; gap: 0;">
                    <div style="width: 90px; height: 90px; flex-shrink: 0; background-image: url('<?= $img ?>'); background-size: cover; background-position: center;"></div>
                    <div style="padding: 14px 16px; display: flex; flex-direction: column; justify-content: center;">
                        <strong style="font-size: 1rem; display: block; margin-bottom: 4px;"><?= htmlspecialchars($salon['name']) ?></strong>
                        <span class="text-muted" style="font-size: 0.82rem;"><i class="fas fa-map-marker-alt text-cyan" style="margin-right: 4px;"></i><?= htmlspecialchars($salon['city']) ?></span>
                        <span class="text-muted" style="font-size: 0.78rem; margin-top: 4px;"><i class="far fa-clock" style="margin-right: 4px;"></i><?= date('g:i A', strtotime($salon['opening_time'])) ?> – <?= date('g:i A', strtotime($salon['closing_time'])) ?></span>
                    </div>
                    <div style="margin-left: auto; padding-right: 16px; display: flex; align-items: center;">
                        <i class="fas fa-chevron-right text-muted"></i>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>
<?php else: ?>
    <!-- Prompt state - no search yet -->
    <div class="card" style="text-align: center; padding: 40px 20px; background: transparent; border: 1px dashed var(--border-color);">
        <i class="fas fa-search" style="font-size: 2.5rem; color: var(--text-muted); margin-bottom: 15px; display: block;"></i>
        <p class="text-muted">Search by salon name or city to find the best salon near you.</p>
    </div>
<?php endif; ?>

<?php require_once 'app/views/layouts/footer.php'; ?>
