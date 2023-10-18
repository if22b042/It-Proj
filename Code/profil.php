<!DOCTYPE html>
<html>
<head>
   <title>Profil</title>
    <link rel="stylesheet" type="text/css" href="profilstile.css"> 
</head>
<?php
session_start();

require_once 'navbar.php';

if(!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: home.php");
    exit();
}

require_once 'dbaccess.php';
$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$email = $_SESSION['email'];
$query1 = "SELECT person.vorname, person.nachname, users.usermail, users.KundenID, person.Telefonummer FROM users JOIN person ON users.PersonID = person.ID WHERE users.usermail='$email'";
$result = mysqli_query($conn, $query1);

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        $vorname = $row["vorname"];
        $nachname = $row["nachname"];
        $usermail = $row["usermail"];
        $telephone = $row["Telefonummer"];
        $UsID= $row["KundenID"];
    }
} else {
    echo "0 results";
}


?>

<body>
    <div>
        <p>Vorname: <?php echo $vorname; ?></p>
        <p>Nachname: <?php echo $nachname; ?></p>
        <p>Email: <?php echo $usermail; ?></p>
        <p>Telefonummer: <?php echo $telephone; ?></p>
        <a href="modify.php"><button>Verändern</button></a>
    </div>

    <div>
        <h2>Reservation Overview</h2>
        <table>
            <tr>
            <th>Ankunft</th>
                <th>Abreise</th>
                <th>Zimmerart</th>
                <th>Anzahl Leute</th>
                <th>Mit Frühstück</th>
                <th>Parkplatz</th>
                <th>Haustiere</th>
                <th>Delete</th> 
            </tr>
            <?php
                $query2 = "SELECT * FROM reservation ";
                $result2 = mysqli_query($conn, $query2);
                $num_reservations = mysqli_num_rows($result2);
                if (mysqli_num_rows($result2) > 0) {
                    while($row = mysqli_fetch_assoc($result2)) {?>
                        <tr>
                            <td><?php echo $row['Ankunft']; ?></td>
                            <td><?php echo $row['Abreise']; ?></td>
                            <td><?php echo $row['Zimmerart']; ?></td>
                            <td><?php echo $row['AnzLeute']; ?></td>
                            <td><?php $Früh=$row['MitFrühstück'];
                            if ($Früh){
                                echo "Ja";
                            }
                            if (!$Früh){
                                echo "Nein";
                            }
                            ?><td><?php $Parkplatz=$row['Parkplatz'];
                            if ($Parkplatz){
                                echo "Ja";
                            }
                            if (!$Parkplatz){
                                echo "Nein";
                            }
                            ?><td><?php $Haustiere=$row['Haustiere'];
                            if ($Haustiere){
                                echo "Ja";
                            }
                            if (!$Haustiere){
                                echo "Nein";
                            }
                            ?>

                            <td><a href="delete-reservation.php?id=<?php echo $row['´ResID'] ; ?>">Delete</a></td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='5'>No reservations found</td></tr>";
                }

            ?>
        </table>
    </div>
</body>
</html>


<head>
    <title>Profil</title>
    <link rel="stylesheet" type="text/css" href="profilstile.css">
</head>