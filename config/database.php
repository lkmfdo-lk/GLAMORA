<?php
// app/config/database.php

class Database
{
    private $host = "localhost";
    private $dbName = "glamora";
    private $username = "root";
    
    public function connect() 
    {
        // Try different combinations for XAMPP (3306/blank) and MAMP (8889/root or 3306/root)
        $attempts = [
            ['port' => 3306, 'pass' => ''],
            ['port' => 8889, 'pass' => 'root'],
            ['port' => 3306, 'pass' => 'root']
        ];
        
        foreach ($attempts as $attempt) {
            try {
                $pdo = new PDO(
                    "mysql:host={$this->host};port={$attempt['port']};dbname={$this->dbName}", 
                    $this->username, 
                    $attempt['pass']
                );
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                return $pdo; 
            } catch (PDOException $e) {
                if (strpos($e->getMessage(), 'Unknown database') !== false) {
                    die("Database 'glamora' does not exist yet. Please import schema.sql in phpMyAdmin first.");
                }
                continue;
            }
        }
        
        die("Database connection failed. Please ensure the MAMP servers have a green light.");
    }
}
