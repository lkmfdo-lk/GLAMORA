<?php
// app/controllers/CustomerController.php

require_once 'app/models/Salon.php';
require_once 'app/models/Service.php';
require_once 'app/models/QueueEntry.php';
require_once 'app/models/User.php';
require_once 'app/helpers/auth_helper.php';
require_once 'app/helpers/location_helper.php';

class CustomerController {
    
    public function __construct() {
        requireRole('customer');
    }
    
    public function dashboard() {
        $salonModel = new Salon();
        $queueModel = new QueueEntry();
        
        $city = $_GET['city'] ?? ''; 
        if ($city) {
            $salons = $salonModel->getApprovedByCity($city);
        } else {
            // Fetch all approved salons if no city is specified
            $salons = $salonModel->getAllApproved();
        }
        
        // Get active queue for customer if any
        $activeQueue = $queueModel->getCustomerCurrentQueue($_SESSION['user_id']);
        if ($activeQueue) {
            // Include estimated wait calculations
            require_once 'app/helpers/queue_helper.php';
            $activeQueue['customers_ahead'] = getCustomersAhead($activeQueue['barber_id'], $activeQueue['joined_at']);
            $activeQueue['estimated_wait'] = calculateEstimatedWait($activeQueue['barber_id'], $activeQueue['joined_at']);
        }
        
        require_once 'app/views/customer/dashboard.php';
    }
    
    public function salonDetails() {
        $id = (int)($_GET['id'] ?? 0);
        
        if (!$id) {
            header('Location: ' . BASE_URL . '/index.php?controller=customer&action=dashboard');
            exit();
        }
        
        $salonModel = new Salon();
        $salon = $salonModel->findById($id);
        
        if (!$salon || $salon['status'] !== 'approved') {
            die("Salon not found or not approved.");
        }
        
        $serviceModel = new Service();
        $services = $serviceModel->getBySalonId($id);
        
        $userModel = new User();
        $barbers = $userModel->getBarbersBySalonId($id);
        
        require_once 'app/views/customer/salon-details.php';
    }
    
    public function search() {
        $salonModel = new Salon();
        $q = trim($_GET['q'] ?? '');
        $salons = [];
        if (!empty($q)) {
            $salons = $salonModel->searchApproved($q);
        }
        require_once 'app/views/customer/search.php';
    }
    
    public function appointments() {
        require_once 'app/models/QueueEntry.php';
        $queueModel = new QueueEntry();
        
        $allEntries = $queueModel->getCustomerAll($_SESSION['user_id']);
        
        $upcoming = [];
        $past = [];
        
        foreach ($allEntries as $entry) {
            if (in_array($entry['status'], ['waiting', 'in_service'])) {
                $upcoming[] = $entry;
            } else {
                $past[] = $entry;
            }
        }
        
        require_once 'app/views/customer/appointments.php';
    }
    
    public function profile() {
        require_once 'app/views/customer/profile.php';
    }
    
    public function editProfile() {
        require_once 'app/models/User.php';
        $userModel = new User();
        
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullName = trim($_POST['full_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            
            if (empty($fullName) || empty($email) || empty($phone)) {
                $error = 'All fields are required.';
            } else {
                if ($userModel->updateProfile($_SESSION['user_id'], $fullName, $email, $phone)) {
                    // Update session data
                    $_SESSION['user_full_name'] = $fullName;
                    $_SESSION['user_email'] = $email;
                    
                    $success = 'Profile updated successfully!';
                } else {
                    $error = 'Failed to update profile. Email might already be taken.';
                }
            }
        }
        
        $user = $userModel->findById($_SESSION['user_id']);
        require_once 'app/views/customer/edit-profile.php';
    }
    
    public function queueDetails() {
        $id = (int)($_GET['id'] ?? 0);
        
        require_once 'app/models/QueueEntry.php';
        $queueModel = new QueueEntry();
        
        $queue = $queueModel->getCustomerCurrentQueue($_SESSION['user_id']);
        
        if (!$queue || $queue['id'] != $id) {
            header('Location: ' . BASE_URL . '/index.php?controller=customer&action=dashboard');
            exit();
        }
        
        // Include estimated wait calculations
        require_once 'app/helpers/queue_helper.php';
        $queue['customers_ahead'] = getCustomersAhead($queue['barber_id'], $queue['joined_at']);
        $queue['estimated_wait'] = calculateEstimatedWait($queue['barber_id'], $queue['joined_at']);
        
        require_once 'app/views/customer/queue-details.php';
    }
}
