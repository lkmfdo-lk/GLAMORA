<?php
// app/controllers/OwnerController.php

require_once 'app/models/User.php';
require_once 'app/models/Salon.php';
require_once 'app/helpers/auth_helper.php';

class OwnerController {
    
    public function register() {
        if (isLoggedIn()) {
            redirectBasedOnRole();
        }
        
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullName = trim($_POST['full_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $password = $_POST['password'] ?? '';
            
            // Salon details
            $salonName = trim($_POST['salon_name'] ?? '');
            $salonPhone = trim($_POST['salon_phone'] ?? '');
            $address = trim($_POST['address'] ?? '');
            $city = trim($_POST['city'] ?? '');
            $openingTime = $_POST['opening_time'] ?? '';
            $closingTime = $_POST['closing_time'] ?? '';
            $description = trim($_POST['description'] ?? '');
            $mapLink = trim($_POST['map_link'] ?? '');
            
            if (empty($fullName) || empty($email) || empty($password) || empty($salonName) || empty($city)) {
                $error = "Please fill in all required fields.";
            } else {
                $userModel = new User();
                
                if ($userModel->findByEmail($email)) {
                    $error = "Email is already registered.";
                } else {
                    // Handle file uploads
                    $uploadDir = 'public/assets/img/salons/';
                    $logoPath = null;
                    $imagePath = null;
                    
                    // Upload logo
                    if (!empty($_FILES['logo']['name'])) {
                        $logoExt = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
                        $logoFilename = 'logo_' . uniqid() . '.' . $logoExt;
                        if (move_uploaded_file($_FILES['logo']['tmp_name'], $uploadDir . $logoFilename)) {
                            $logoPath = $uploadDir . $logoFilename;
                        }
                    }
                    
                    // Upload salon image
                    if (!empty($_FILES['image']['name'])) {
                        $imgExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                        $imgFilename = 'img_' . uniqid() . '.' . $imgExt;
                        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imgFilename)) {
                            $imagePath = $uploadDir . $imgFilename;
                        }
                    }
                    
                    $db = (new Database())->connect();
                    try {
                        $db->beginTransaction();
                        
                        // 1. Create Owner User
                        $ownerId = $userModel->createOwner($fullName, $email, $phone, $password);
                        
                        if ($ownerId) {
                            // 2. Create Salon with images and map link
                            $salonModel = new Salon();
                            $salonModel->create($ownerId, $salonName, $salonPhone, $address, $city, $openingTime, $closingTime, $description, $logoPath, $imagePath, $mapLink);
                            
                            $db->commit();
                            $success = "Registration successful! Your salon is pending admin approval. You can login now.";
                        } else {
                            throw new Exception("Could not create user account.");
                        }
                    } catch (Exception $e) {
                        $db->rollBack();
                        $error = "Registration failed: " . $e->getMessage();
                    }
                }
            }
        }
        
        require_once 'app/views/auth/owner-register.php';
    }
    
    public function dashboard() {
        requireRole('owner');
        // Fetch salon details for dashboard
        $salonModel = new Salon();
        $salon = $salonModel->findByOwnerId($_SESSION['user_id']);
        
        if ($salon && $salon['status'] === 'approved') {
            require_once 'app/models/QueueEntry.php';
            require_once 'app/models/User.php';
            
            $db = (new Database())->connect();
            
            // Stats
            $stmt = $db->prepare("SELECT COUNT(*) AS waiting_now FROM queue_entries WHERE salon_id = ? AND status = 'waiting'");
            $stmt->execute([$salon['id']]);
            $waitingNow = $stmt->fetchColumn();
            
            $stmt = $db->prepare("SELECT COUNT(*) AS completed_today FROM queue_entries WHERE salon_id = ? AND status = 'completed' AND DATE(finished_at) = CURDATE()");
            $stmt->execute([$salon['id']]);
            $completedToday = $stmt->fetchColumn();
            
            $userModel = new User();
            $barbers = $userModel->getBarbersBySalonId($salon['id']);
            
            $_SESSION['user_salon_id'] = $salon['id'];
        }
        
        require_once 'app/views/owner/dashboard.php';
    }
    
    public function queue() {
        requireRole('owner');
        
        $db = (new Database())->connect();
        $salonId = $_SESSION['user_salon_id'] ?? 0;
        
        $stmt = $db->prepare("
            SELECT 
                queue_entries.*,
                users.full_name AS customer_name,
                services.name AS service_name,
                barbers.full_name AS barber_name
            FROM queue_entries
            JOIN users ON queue_entries.customer_id = users.id
            JOIN services ON queue_entries.service_id = services.id
            JOIN users barbers ON queue_entries.barber_id = barbers.id
            WHERE queue_entries.salon_id = ? AND queue_entries.status IN ('waiting', 'in_service')
            ORDER BY queue_entries.status ASC, queue_entries.joined_at ASC
        ");
        $stmt->execute([$salonId]);
        $queue = $stmt->fetchAll();
        
        require_once 'app/views/owner/queue.php';
    }
}
