<?php
// app/helpers/queue_helper.php

/**
 * Calculates the number of waiting customers ahead of a given join time.
 */
function getCustomersAhead($barberId, $joinedAt) {
    $db = (new Database())->connect();
    
    $stmt = $db->prepare("
        SELECT COUNT(*) AS customers_ahead 
        FROM queue_entries 
        WHERE barber_id = ? 
        AND status = 'waiting' 
        AND joined_at < ?
    ");
    
    $stmt->execute([$barberId, $joinedAt]);
    $result = $stmt->fetch();
    
    return (int)($result['customers_ahead'] ?? 0);
}

/**
 * Calculates the estimated waiting time in minutes based on the services 
 * of the customers ahead in the queue.
 */
function calculateEstimatedWait($barberId, $joinedAt) {
    $db = (new Database())->connect();
    
    // First find if there's someone in_service right now.
    $stmtInService = $db->prepare("
        SELECT services.duration_minutes, queue_entries.started_at 
        FROM queue_entries 
        JOIN services ON queue_entries.service_id = services.id 
        WHERE queue_entries.barber_id = ? 
        AND queue_entries.status = 'in_service'
        LIMIT 1
    ");
    $stmtInService->execute([$barberId]);
    $inService = $stmtInService->fetch();
    
    $remainingInService = 0;
    if ($inService && $inService['started_at']) {
        $startedTime = strtotime($inService['started_at']);
        $currentTime = time();
        $elapsed = floor(($currentTime - $startedTime) / 60);
        $remainingInService = max(0, $inService['duration_minutes'] - $elapsed);
    }
    
    // Now sum durations of waiting customers ahead
    $stmt = $db->prepare("
        SELECT SUM(services.duration_minutes) AS estimated_wait 
        FROM queue_entries 
        JOIN services ON queue_entries.service_id = services.id 
        WHERE queue_entries.barber_id = ? 
        AND queue_entries.status = 'waiting' 
        AND queue_entries.joined_at < ?
    ");
    
    $stmt->execute([$barberId, $joinedAt]);
    $result = $stmt->fetch();
    
    $waitingWait = (int)($result['estimated_wait'] ?? 0);
    
    return $remainingInService + $waitingWait;
}

/**
 * Very basic auto-assignment. 
 * Finds the barber with the shortest current waiting queue duration.
 */
function assignBestBarber($salonId) {
    $db = (new Database())->connect();
    
    // Find all active barbers in salon
    $stmt = $db->prepare("
        SELECT id FROM users 
        WHERE salon_id = ? AND role = 'barber' AND status = 'active'
    ");
    $stmt->execute([$salonId]);
    $barbers = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($barbers)) return null;
    
    $bestBarberId = $barbers[0];
    $minWait = PHP_INT_MAX;
    
    foreach ($barbers as $bId) {
        // Calculate total wait for this barber
        $wait = calculateEstimatedWait($bId, date('Y-m-d H:i:s')); // Now
        if ($wait < $minWait) {
            $minWait = $wait;
            $bestBarberId = $bId;
        }
    }
    
    return $bestBarberId;
}
