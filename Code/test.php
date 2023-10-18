<?php
require_once 'dbaccess.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['username']) && isset($_POST['password'])) {
        $conn = new mysqli($host, $user, $password, $database);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $stmt = $conn->prepare("SELECT hashedPassword, salt FROM users WHERE username=asd");


        $stmt->bind_result($hashedPassword, $salt);
        $password="ale";
        if (password_verify($password . $salt, $hashedPassword)){
            echo "idaasdf";
        }
        else {
            echo "error";
        }
    }
}
?>
