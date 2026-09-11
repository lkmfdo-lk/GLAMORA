<?php
// app/controllers/AdminController.php

require_once 'app/models/Salon.php';
require_once 'app/models/User.php';
require_once 'app/helpers/auth_helper.php';

class AdminController {
    
    public function __construct() {
        // Ensure all methods require admin role
        requireRole('admin');
    }
    
    public function dashboard() {
        $salonModel = new Salon();
        
        $stats = $salonModel->getAllStats();
        $pendingSalons = $salonModel->getPending();
        
        require_once 'app/views/admin/dashboard.php';
    }
    
    public function approveSalon() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $salonId = $_POST['salon_id'] ?? 0;
            
            if ($salonId) {
                $salonModel = new Salon();
                $salonModel->approve($salonId);
            }
        }
        header('Location: ' . BASE_URL . '/index.php?controller=admin&action=dashboard');
        exit();
    }
    
    public function rejectSalon() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $salonId = $_POST['salon_id'] ?? 0;
            $reason = trim($_POST['rejection_reason'] ?? '');
            
            if ($salonId) {
                $salonModel = new Salon();
                $salonModel->reject($salonId, $reason);
            }
        }
        header('Location: ' . BASE_URL . '/index.php?controller=admin&action=dashboard');
        exit();
    }
}
