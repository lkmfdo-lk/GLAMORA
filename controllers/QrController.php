<?php
// app/controllers/QrController.php

require_once 'app/models/QueueEntry.php';
require_once 'app/models/User.php';
require_once 'app/helpers/auth_helper.php';

class QrController {
    
    public function scan() {
        requireRole('barber');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = strtoupper(trim($_POST['qr_token'] ?? ''));
            
            if ($token) {
                $queueModel = new QueueEntry();
                $entry = $queueModel->findByToken($token);
                
                // Get barber's actual salon_id fresh from DB (session may be stale)
                $userModel = new User();
                $barber = $userModel->findById($_SESSION['user_id']);
                $barberSalonId = $barber['salon_id'] ?? null;
                
                if ($entry) {
                    if ($entry['status'] !== 'waiting') {
                        $error = "This token is already " . $entry['status'] . ".";
                    } elseif (!$barberSalonId || $entry['salon_id'] != $barberSalonId) {
                        $error = "This queue entry belongs to a different salon.";
                    } else {
                        // Start service
                        $db = (new Database())->connect();
                        try {
                            $db->beginTransaction();
                            
                            $queueModel->startService($entry['id']);
                            $userModel->updateAvailability($_SESSION['user_id'], 'busy');
                            
                            $db->commit();
                            
                            header('Location: ' . BASE_URL . '/index.php?controller=barber&action=dashboard');
                            exit();
                        } catch (Exception $e) {
                            $db->rollBack();
                            $error = "Error starting service.";
                        }
                    }
                } else {
                    $error = "Invalid QR Token.";
                }
            }
        }
        
        require_once 'app/views/barber/scan-qr.php';
    }
    
    public function finishService() {
        requireRole('barber');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['queue_id'] ?? 0);
            
            if ($id) {
                $db = (new Database())->connect();
                try {
                    $db->beginTransaction();
                    
                    $queueModel = new QueueEntry();
                    $queueModel->finishService($id);
                    
                    $userModel = new User();
                    $userModel->updateAvailability($_SESSION['user_id'], 'available');
                    
                    $db->commit();
                } catch (Exception $e) {
                    $db->rollBack();
                }
            }
        }
        
        header('Location: ' . BASE_URL . '/index.php?controller=barber&action=dashboard');
        exit();
    }
    
    public function markNoShow() {
        requireRole('barber');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['queue_id'] ?? 0);
            
            if ($id) {
                $queueModel = new QueueEntry();
                $queueModel->markNoShow($id, $_SESSION['user_salon_id']);
            }
        }
        
        header('Location: ' . BASE_URL . '/index.php?controller=barber&action=dashboard');
        exit();
    }
}
