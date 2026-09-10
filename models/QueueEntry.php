<?php
// app/models/QueueEntry.php

class QueueEntry {
    private $pdo;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->connect();
    }
    
    public function getCustomerCurrentQueue($customerId) {
        $stmt = $this->pdo->prepare("
            SELECT 
                queue_entries.*, 
                salons.name AS salon_name, 
                services.name AS service_name, 
                users.full_name AS barber_name
            FROM queue_entries
            JOIN salons ON queue_entries.salon_id = salons.id
            JOIN services ON queue_entries.service_id = services.id
            JOIN users ON queue_entries.barber_id = users.id
            WHERE queue_entries.customer_id = ? 
            AND queue_entries.status IN ('waiting', 'in_service')
            LIMIT 1
        ");
        $stmt->execute([$customerId]);
        return $stmt->fetch();
    }
    
    public function checkCustomerInQueue($customerId, $salonId) {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) AS total
            FROM queue_entries
            WHERE customer_id = ? 
            AND salon_id = ?
            AND status IN ('waiting', 'in_service')
        ");
        $stmt->execute([$customerId, $salonId]);
        $result = $stmt->fetch();
        return $result['total'] > 0;
    }
    
    public function getCustomerAll($customerId) {
        $stmt = $this->pdo->prepare("
            SELECT 
                queue_entries.*, 
                salons.name AS salon_name, 
                services.name AS service_name, 
                users.full_name AS barber_name
            FROM queue_entries
            JOIN salons ON queue_entries.salon_id = salons.id
            JOIN services ON queue_entries.service_id = services.id
            JOIN users ON queue_entries.barber_id = users.id
            WHERE queue_entries.customer_id = ? 
            ORDER BY queue_entries.joined_at DESC
        ");
        $stmt->execute([$customerId]);
        return $stmt->fetchAll();
    }
    
    public function create($customerId, $salonId, $serviceId, $barberId, $qrToken) {
        $stmt = $this->pdo->prepare("
            INSERT INTO queue_entries 
            (customer_id, salon_id, service_id, barber_id, qr_token, status, joined_at) 
            VALUES 
            (?, ?, ?, ?, ?, 'waiting', NOW())
        ");
        return $stmt->execute([$customerId, $salonId, $serviceId, $barberId, $qrToken]);
    }
    
    public function findByToken($token) {
        $stmt = $this->pdo->prepare("
            SELECT 
                queue_entries.*,
                users.full_name AS customer_name,
                services.name AS service_name
            FROM queue_entries
            JOIN users ON queue_entries.customer_id = users.id
            JOIN services ON queue_entries.service_id = services.id
            WHERE queue_entries.qr_token = ?
            LIMIT 1
        ");
        $stmt->execute([$token]);
        return $stmt->fetch();
    }
    
    public function startService($id) {
        $stmt = $this->pdo->prepare("
            UPDATE queue_entries 
            SET status = 'in_service', started_at = NOW() 
            WHERE id = ? AND status = 'waiting'
        ");
        return $stmt->execute([$id]);
    }
    
    public function finishService($id) {
        $stmt = $this->pdo->prepare("
            UPDATE queue_entries 
            SET status = 'completed', finished_at = NOW() 
            WHERE id = ? AND status = 'in_service'
        ");
        return $stmt->execute([$id]);
    }
    
    public function cancel($id, $customerId) {
        $stmt = $this->pdo->prepare("
            UPDATE queue_entries 
            SET status = 'cancelled' 
            WHERE id = ? AND customer_id = ? AND status = 'waiting'
        ");
        return $stmt->execute([$id, $customerId]);
    }
    
    public function markNoShow($id, $salonId) {
        $stmt = $this->pdo->prepare("
            UPDATE queue_entries 
            SET status = 'no_show' 
            WHERE id = ? AND salon_id = ? AND status = 'waiting'
        ");
        return $stmt->execute([$id, $salonId]);
    }
    
    public function getBarberQueue($barberId) {
        $stmt = $this->pdo->prepare("
            SELECT 
                queue_entries.id,
                users.full_name AS customer_name,
                services.name AS service_name,
                queue_entries.joined_at,
                queue_entries.status
            FROM queue_entries
            JOIN users ON queue_entries.customer_id = users.id
            JOIN services ON queue_entries.service_id = services.id
            WHERE queue_entries.barber_id = ? 
            AND queue_entries.status IN ('waiting', 'in_service')
            ORDER BY queue_entries.status ASC, queue_entries.joined_at ASC
        ");
        $stmt->execute([$barberId]);
        return $stmt->fetchAll();
    }
}
