<?php
// app/models/Salon.php

class Salon {
    private $pdo;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->connect();
    }
    
    public function create($ownerId, $name, $phone, $address, $city, $openingTime, $closingTime, $description, $logo = null, $image = null, $mapLink = null) {
        $stmt = $this->pdo->prepare("
            INSERT INTO salons 
            (owner_id, name, phone, address, city, opening_time, closing_time, description, logo, image, map_link, status) 
            VALUES 
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')
        ");
        
        return $stmt->execute([$ownerId, $name, $phone, $address, $city, $openingTime, $closingTime, $description, $logo, $image, $mapLink]);
    }
    
    public function findByOwnerId($ownerId) {
        $stmt = $this->pdo->prepare("
            SELECT * 
            FROM salons 
            WHERE owner_id = ? 
            LIMIT 1
        ");
        $stmt->execute([$ownerId]);
        return $stmt->fetch();
    }
    
    public function getPending() {
        $stmt = $this->pdo->prepare("
            SELECT salons.*, users.full_name AS owner_name, users.email AS owner_email 
            FROM salons 
            JOIN users ON salons.owner_id = users.id 
            WHERE salons.status = 'pending' 
            ORDER BY salons.created_at ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getApproved() {
        $stmt = $this->pdo->prepare("
            SELECT * 
            FROM salons 
            WHERE status = 'approved'
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getApprovedByCity($city) {
        $stmt = $this->pdo->prepare("
            SELECT * 
            FROM salons 
            WHERE status = 'approved' AND city = ?
        ");
        $stmt->execute([$city]);
        return $stmt->fetchAll();
    }
    
    public function getAllApproved() {
        $stmt = $this->pdo->query("
            SELECT * 
            FROM salons 
            WHERE status = 'approved'
            ORDER BY created_at DESC
        ");
        return $stmt->fetchAll();
    }
    
    public function searchApproved($query) {
        $like = '%' . $query . '%';
        $stmt = $this->pdo->prepare("
            SELECT * 
            FROM salons 
            WHERE status = 'approved'
            AND (name LIKE ? OR city LIKE ? OR address LIKE ?)
            ORDER BY name ASC
        ");
        $stmt->execute([$like, $like, $like]);
        return $stmt->fetchAll();
    }
    
    public function approve($id) {
        $stmt = $this->pdo->prepare("
            UPDATE salons 
            SET status = 'approved', rejection_reason = NULL 
            WHERE id = ?
        ");
        return $stmt->execute([$id]);
    }
    
    public function reject($id, $reason) {
        $stmt = $this->pdo->prepare("
            UPDATE salons 
            SET status = 'rejected', rejection_reason = ? 
            WHERE id = ?
        ");
        return $stmt->execute([$reason, $id]);
    }
    
    public function findById($id) {
        $stmt = $this->pdo->prepare("
            SELECT * 
            FROM salons 
            WHERE id = ? 
            LIMIT 1
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function getAllStats() {
        $stmt = $this->pdo->prepare("
            SELECT 
                COUNT(*) as total_salons,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_salons,
                SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_salons,
                SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected_salons
            FROM salons
        ");
        $stmt->execute();
        return $stmt->fetch();
    }
}
