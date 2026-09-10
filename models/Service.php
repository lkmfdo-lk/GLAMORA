<?php
// app/models/Service.php

class Service {
    private $pdo;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->connect();
    }
    
    public function getBySalonId($salonId) {
        $stmt = $this->pdo->prepare("
            SELECT * 
            FROM services 
            WHERE salon_id = ? AND is_active = 1
            ORDER BY name ASC
        ");
        $stmt->execute([$salonId]);
        return $stmt->fetchAll();
    }
    
    public function create($salonId, $name, $description, $duration, $price) {
        $stmt = $this->pdo->prepare("
            INSERT INTO services 
            (salon_id, name, description, duration_minutes, price, is_active) 
            VALUES 
            (?, ?, ?, ?, ?, 1)
        ");
        return $stmt->execute([$salonId, $name, $description, $duration, $price]);
    }
    
    public function deactivate($id, $salonId) {
        $stmt = $this->pdo->prepare("
            UPDATE services 
            SET is_active = 0 
            WHERE id = ? AND salon_id = ?
        ");
        return $stmt->execute([$id, $salonId]);
    }
}
