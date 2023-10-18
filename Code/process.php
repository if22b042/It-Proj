<?php
session_start();
require_once 'dbaccess.php';
$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$website = $_POST['website'];
$password = $_POST['password'];
$email = $_POST['email'];
$username = $_POST['username'];

// Get UserID from session
$email2 = $_SESSION['email'];
$sql = "SELECT id FROM users WHERE email='$email2'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$userID = $row['id'];

// Insert data into Database
$sql = "INSERT INTO Passwordstore (Website, pass, Email, Username, UserID) VALUES ('$website', '$password', '$email', '$username', '$userID')";

if ($conn->query($sql) === TRUE) {
    echo "Record added successfully";
    header("Location: main.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
