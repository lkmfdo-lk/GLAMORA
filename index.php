<?php
// public/index.php

require_once 'app/config/config.php';
require_once 'app/config/database.php';

// Simple Routing
$controllerName = isset($_GET['controller']) ? $_GET['controller'] : 'auth';
$actionName = isset($_GET['action']) ? $_GET['action'] : 'login';

// Capitalize controller name and append 'Controller'
$controllerClass = ucfirst($controllerName) . 'Controller';
$controllerFile = 'app/controllers/' . $controllerClass . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    
    if (class_exists($controllerClass)) {
        $controller = new $controllerClass();
        
        if (method_exists($controller, $actionName)) {
            // Call the action
            $controller->$actionName();
        } else {
            die("Action '$actionName' not found in controller '$controllerClass'.");
        }
    } else {
        die("Controller class '$controllerClass' not found.");
    }
} else {
    die("Controller file '$controllerFile' not found.");
}
