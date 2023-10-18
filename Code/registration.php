<?php
require_once 'dbaccess.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$stmt = null; 

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //setup Database connection
    $conn = new mysqli($host, $user, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordconfirm = $_POST['passwordconfirm'];

    // Check if username or email already exist
    $checkUserEmail = $conn->prepare("SELECT * FROM users WHERE username=? OR email=?");
    if (!$checkUserEmail) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }

    $checkUserEmail->bind_param("ss", $username, $email);
    $checkUserEmail->execute();
    $checkUserEmail->store_result();

    if ($checkUserEmail->num_rows > 0) {
        echo "Username or email already exists. Please choose a different one.";
    } else {
        if ($password == $passwordconfirm) {
            
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            //add to database
            $stmt = $conn->prepare("INSERT INTO users (username, email, hashedPassword) VALUES (?, ?, ?)");

            if ($stmt) {
                $stmt->bind_param("sss", $username, $email, $hashedPassword);
                if ($stmt->execute()) {
                    echo "Registration successful. You can now login.";
                    $_SESSION['logged_in'] = true;
                    $_SESSION['email'] = $email;
                    header("Location: main.php");
                } else {
                    echo "Error during execution: " . $stmt->error;
                }
            } else {
                echo "Error during registration. Please try again.";
            }

        } else {
            echo "Passwords do not match. Please try again.";
        }
    }

    $checkUserEmail->close();

    $conn->close();
}
?>
