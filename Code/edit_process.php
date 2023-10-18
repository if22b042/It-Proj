<?php
session_start();

if(!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: home.html");
    exit();
}

require_once 'dbaccess.php';
$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$entryID = $_POST['entry_id'];
$website = $_POST['edit_website'];
$password = $_POST['edit_password'];
$email = $_POST['edit_email'];
$username = $_POST['edit_username'];

$sql = "UPDATE Passwordstore SET Website='$website', pass='$password', Email='$email', Username='$username' WHERE PasID='$entryID'";
$result = $conn->query($sql);

if ($result) {
    echo "Record updated successfully";
    header("Location: main.php");
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();
?>
