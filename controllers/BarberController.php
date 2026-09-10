<?php
// app/controllers/BarberController.php

require_once 'app/models/User.php';
require_once 'app/helpers/auth_helper.php';

class BarberController {
    
    public function list() {
        requireRole('owner');
        
        $userModel = new User();
        $barbers = $userModel->getBarbersBySalonId($_SESSION['user_salon_id'] ?? 0);
        
        require_once 'app/views/owner/barbers.php';
    }
    
    public function add() {
        requireRole('owner');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullName = trim($_POST['full_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if ($fullName && $email && $password) {
                $userModel = new User();
                if (!$userModel->findByEmail($email)) {
                    $userModel->createBarber($fullName, $email, $phone, $password, $_SESSION['user_salon_id']);
                }
            }
        }
        header('Location: ' . BASE_URL . '/index.php?controller=barber&action=list');
        exit();
    }
    
    // For barber dashboard
    public function dashboard() {
        requireRole('barber');
        
        require_once 'app/models/QueueEntry.php';
        $queueModel = new QueueEntry();
        $myQueue = $queueModel->getBarberQueue($_SESSION['user_id']);
        
        require_once 'app/views/barber/dashboard.php';
    }
}
