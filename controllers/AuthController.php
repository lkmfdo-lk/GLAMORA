<?php
// app/controllers/AuthController.php

require_once 'app/models/User.php';
require_once 'app/helpers/auth_helper.php';

class AuthController {
    
    public function login() {
        // If already logged in, redirect
        if (isLoggedIn()) {
            redirectBasedOnRole();
        }
        
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if (empty($email) || empty($password)) {
                $error = "Please fill in all fields.";
            } else {
                $userModel = new User();
                $user = $userModel->findByEmail($email);
                
                if ($user && password_verify($password, $user['password'])) {
                    if ($user['status'] === 'inactive') {
                        $error = "Your account is deactivated. Please contact support.";
                    } else {
                        // Setup session
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['user_full_name'] = $user['full_name'];
                        $_SESSION['user_email'] = $user['email'];
                        $_SESSION['user_role'] = $user['role'];
                        $_SESSION['user_salon_id'] = $user['salon_id'];
                        
                        redirectBasedOnRole();
                    }
                } else {
                    $error = "Invalid email or password.";
                }
            }
        }
        
        require_once 'app/views/auth/login.php';
    }
    
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
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            if (empty($fullName) || empty($email) || empty($phone) || empty($password)) {
                $error = "All fields are required.";
            } elseif ($password !== $confirmPassword) {
                $error = "Passwords do not match.";
            } else {
                $userModel = new User();
                
                // Check if email already exists
                if ($userModel->findByEmail($email)) {
                    $error = "Email is already registered.";
                } else {
                    if ($userModel->createCustomer($fullName, $email, $phone, $password)) {
                        $_SESSION['flash_success'] = "Account created successfully! Please login.";
                        header('Location: ' . BASE_URL . '/index.php?controller=auth&action=login');
                        exit();
                    } else {
                        $error = "Registration failed. Please try again.";
                    }
                }
            }
        }
        
        require_once 'app/views/auth/register.php';
    }
    
    public function logout() {
        
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL . '/index.php?controller=auth&action=login');
        exit();
    }
}
