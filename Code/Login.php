<?php
require_once 'dbaccess.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //connect database
    if(isset($_POST['username']) && isset($_POST['password'])) {
        $conn = new mysqli($host, $user, $password, $database);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $username = $_POST['username'];
        $password = $_POST['password'];
        
        $stmt = $conn->prepare("SELECT hashedPassword, email FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($hashedPassword, $email);
        
        if ($stmt->fetch() && password_verify($password, $hashedPassword)) {
            echo "Welcome, $username!";
            $_SESSION['logged_in'] = true;
            $_SESSION['email'] = $email; // Set the email in the session
            header("Location: main.php");//If Login Successfull then send to Home
        } else {
            echo "Invalid credentials. Please try again." . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Please fill out both username and password fields.";
    }
}
?>
