<?php
session_start();

if(!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: home.html");
    exit();
}

if(isset($_GET['id'])) {
    require_once 'dbaccess.php';
    $conn = new mysqli($host, $user, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $entryID = $_GET['id'];

    $sql = "DELETE FROM Passwordstore WHERE PasID='$entryID'";
    $result = $conn->query($sql);

    if ($result) {
        echo "Record deleted successfully";
        header("Location: main.php");
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Invalid entry ID";
}
?>
