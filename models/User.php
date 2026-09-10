<?php
// app/models/User.php

class User {
    private $pdo;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->connect();
    }

    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("
            SELECT * 
            FROM users 
            WHERE email = ? 
            LIMIT 1
        ");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
    
    public function findById($id) {
        $stmt = $this->pdo->prepare("
            SELECT * 
            FROM users 
            WHERE id = ? 
            LIMIT 1
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function createCustomer($fullName, $email, $phone, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $this->pdo->prepare("
            INSERT INTO users 
            (full_name, email, phone, password, role) 
            VALUES 
            (?, ?, ?, ?, 'customer')
        ");
        
        return $stmt->execute([$fullName, $email, $phone, $hashedPassword]);
    }
    
    public function createOwner($fullName, $email, $phone, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $this->pdo->prepare("
            INSERT INTO users 
            (full_name, email, phone, password, role) 
            VALUES 
            (?, ?, ?, ?, 'owner')
        ");
        
        if ($stmt->execute([$fullName, $email, $phone, $hashedPassword])) {
            return $this->pdo->lastInsertId();
        }
        return false;
    }
    
    public function createBarber($fullName, $email, $phone, $password, $salonId) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $this->pdo->prepare("
            INSERT INTO users 
            (full_name, email, phone, password, role, salon_id) 
            VALUES 
            (?, ?, ?, ?, 'barber', ?)
        ");
        
        return $stmt->execute([$fullName, $email, $phone, $hashedPassword, $salonId]);
    }
    
    public function getBarbersBySalonId($salonId) {
        $stmt = $this->pdo->prepare("
            SELECT id, full_name, email, phone, status, availability_status 
            FROM users 
            WHERE role = 'barber' AND salon_id = ?
        ");
        $stmt->execute([$salonId]);
        return $stmt->fetchAll();
    }
    
    public function updateAvailability($id, $status) {
        $stmt = $this->pdo->prepare("
            UPDATE users 
            SET availability_status = ? 
            WHERE id = ?
        ");
        return $stmt->execute([$status, $id]);
    }

    public function updateProfile($id, $fullName, $email, $phone) {
        $stmt = $this->pdo->prepare("
            UPDATE users 
            SET full_name = ?, email = ?, phone = ? 
            WHERE id = ?
        ");
        return $stmt->execute([$fullName, $email, $phone, $id]);
    }
}
