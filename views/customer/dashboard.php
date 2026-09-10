<?php require_once 'app/views/layouts/header.php'; ?>

<!-- Functional Search Bar -->
<form action="<?= BASE_URL ?>/index.php" method="GET" class="form-group mt-3 position-relative" id="search">
    <input type="hidden" name="controller" value="customer">
    <input type="hidden" name="action" value="dashboard">
    <div style="position: relative; display: flex; align-items: center; gap: 10px;">
        <div style="position: relative; flex: 1;">
            <i class="fas fa-search" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
            <input type="text" name="city" class="form-control search-input" placeholder="Search by city..." value="<?= isset($_GET['city']) ? htmlspecialchars($_GET['city']) : '' ?>" style="padding-left: 45px; background: var(--primary-color); border: none; border-radius: 50px; color: white;">
        </div>
        <button type="submit" style="background: var(--secondary-color); width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0; border: none;">
            <i class="fas fa-sliders-h" style="color: #000; font-size: 1.1rem;"></i>
        </button>
    </div>
</form>

<?php if(isset($activeQueue) && $activeQueue): ?>
    
    <div class="section-header mt-2">
        <h3 class="section-title">Live Queue</h3>
        <a href="<?= BASE_URL ?>/index.php?controller=customer&action=queueDetails&id=<?= $activeQueue['id'] ?>" class="section-link text-cyan">See details</a>
    </div>

    <!-- Stylized like the "Eid Offers" card in the image -->
    <div class="card card-cyan mb-4" style="display: flex; flex-direction: row; border-radius: 20px; overflow: hidden; position: relative;">
        <div style="flex: 1; padding: 24px; display: flex; flex-direction: column; justify-content: center;">
            <p class="text-muted" style="font-size: 0.9rem; font-weight: 500; margin-bottom: 5px;"><?= htmlspecialchars($activeQueue['salon_name']) ?></p>
            <h2 style="font-size: 1.8rem; font-weight: 700; line-height: 1.1; margin-bottom: 5px;"><?= htmlspecialchars($activeQueue['service_name']) ?></h2>
            <p style="font-size: 0.9rem; margin-bottom: 15px; font-weight: 500;">Est: <span id="live-eta"><?= $activeQueue['estimated_wait'] ?></span> Mins</p>
            
            <a href="<?= BASE_URL ?>/index.php?controller=customer&action=queueDetails&id=<?= $activeQueue['id'] ?>" class="btn" style="background: white; color: #000; width: fit-content; padding: 8px 16px; font-size: 0.85rem;">
                Queue #<span id="live-customers-ahead"><?= $activeQueue['customers_ahead'] ?></span> <i class="fas fa-chevron-right" style="margin-left: 5px;"></i>
            </a>
            
            <?php if($activeQueue['status'] === 'waiting'): ?>
                <form action="<?= BASE_URL ?>/index.php?controller=queue&action=cancel" method="POST" style="position: absolute; top: 15px; right: 15px; z-index: 10;">
                    <input type="hidden" name="queue_id" value="<?= $activeQueue['id'] ?>">
                    <button type="submit" class="btn-outline confirm-action" style="border: none; background: rgba(0,0,0,0.1); border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;"><i class="fas fa-times text-dark"></i></button>
                </form>
            <?php endif; ?>
        </div>
        <div style="width: 140px; background-image: url('https://images.unsplash.com/photo-1599351431202-1e0f0137899a?auto=format&fit=crop&w=300&q=80'); background-size: cover; background-position: center; border-radius: 0 20px 20px 0;">
        </div>
    </div>
    
    <div style="display:none;" id="qrcode-data"><?= htmlspecialchars($activeQueue['qr_token']) ?></div>
    
    <script>
        // Live polling every 10 seconds
        setInterval(function() {
            fetch('<?= BASE_URL ?>/index.php?controller=queue&action=statusApi')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'none' || data.status === 'completed' || data.status === 'cancelled' || data.status === 'no_show') {
                        window.location.reload();
                    } else if (data.status === 'in_service') {
                        document.getElementById('live-customers-ahead').innerText = 'Serving';
                        document.getElementById('live-eta').innerText = '0';
                    } else {
                        document.getElementById('live-customers-ahead').innerText = data.customers_ahead;
                        document.getElementById('live-eta').innerText = data.estimated_wait;
                    }
                })
                .catch(error => console.error('Error fetching live status:', error));
        }, 10000);
    </script>
<?php endif; ?>


<!-- Category Pills for UI -->
<div class="category-pills mt-4">
    <div class="pill active">All</div>
    <div class="pill">Haircuts</div>
    <div class="pill">Facial</div>
    <div class="pill">Hairdo</div>
    <div class="pill">Massage</div>
</div>

<div class="section-header mt-4">
    <h3 class="section-title">Nearby salons</h3>
    <a href="#" class="section-link text-cyan">See all</a>
</div>

<div class="salons-grid mb-4" id="salon-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px;">
    <?php if(empty($salons)): ?>
        <p class="text-muted">No salons found in this city.</p>
    <?php else: ?>
        <?php foreach($salons as $index => $salon): ?>
            <?php 
            // Use uploaded image if available, otherwise fall back to a default
            $defaultImages = [
                BASE_URL . '/public/assets/img/branding/salon1.jpg',
                BASE_URL . '/public/assets/img/branding/salon2.jpg',
                BASE_URL . '/public/assets/img/branding/salon3.jpg',
            ];
            $defaultImg = 'https://images.unsplash.com/photo-1560066984-138dadb4c035?auto=format&fit=crop&w=500&q=80';
            $img = !empty($salon['image']) ? BASE_URL . '/' . $salon['image'] : $defaultImg;
            $logo = !empty($salon['logo']) ? BASE_URL . '/' . $salon['logo'] : null;
            $categories = ['haircuts', 'facial', 'hairdo', 'massage'];
            $randomCategory = $categories[$index % count($categories)];
            ?>
            <a href="<?= BASE_URL ?>/index.php?controller=customer&action=salonDetails&id=<?= $salon['id'] ?>" class="salon-card" data-category="<?= $randomCategory ?>" style="display: block; text-decoration: none; color: inherit;">
                <img src="<?= $img ?>" alt="Salon image">
                <div class="salon-card-content">
                    <div class="salon-card-title"><?= htmlspecialchars($salon['name']) ?></div>
                    <div class="text-muted" style="font-size: 0.85rem; margin-bottom: 8px;">
                        <?= htmlspecialchars(substr($salon['description'], 0, 40)) ?>...
                    </div>
                    <div class="salon-card-meta">
                        <span class="rating"><i class="fas fa-star" style="margin-right: 4px;"></i>4.<?= rand(5,9) ?> <span class="text-muted" style="font-weight: normal;">| 120 Views</span></span>
                        <div class="btn-primary" style="width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-arrow-right" style="font-size: 0.8rem;"></i>
                        </div>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Simple filtering script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const pills = document.querySelectorAll('.category-pills .pill');
        const cards = document.querySelectorAll('.salon-card');
        
        pills.forEach(pill => {
            pill.addEventListener('click', function() {
                // Update active state
                pills.forEach(p => p.classList.remove('active'));
                this.classList.add('active');
                
                // Filter cards
                const filter = this.textContent.toLowerCase().trim();
                
                cards.forEach(card => {
                    if (filter === 'all' || card.getAttribute('data-category') === filter) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    });
</script>

<?php require_once 'app/views/layouts/footer.php'; ?>
