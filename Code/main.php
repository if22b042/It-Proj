<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Manager</title>
    <link rel="stylesheet" href="main.css"> 
</head>
<body>
    <div class="top-buttons">
        <button id="logoutButton">Log Out</button>
        <button id="addButton">Add Password</button>
    </div>

    <div class="modal" id="addModal">
        <div class="modal-content">
            <span class="close" id="closeButton">&times;</span>
            <form action="process.php" method="post">
                Website: <input type="text" name="website"><br>
                Password: <input type="password" name="password" id="password"><br>
                Generate Password: 
                <input type="checkbox" id="generatePassword" onchange="togglePasswordInput()"><br>
                Password Length: 
                <input type="number" id="passwordLength" value="12" min="8" max="32"><br>
                Email: <input type="text" name="email"><br>
                Username: <input type="text" name="username"><br>
                <input type="submit" value="Submit">
            </form>
        </div>
    </div>

    <div class="modal" id="editModal">
        <div class="modal-content">
            <span class="close" id="closeEditButton">&times;</span>
            <form action="edit_process.php" method="post">
                <input type="hidden" name="entry_id" id="editEntryID">
                Website: <input type="text" name="edit_website" id="editWebsite"><br>
                Password: <input type="password" name="edit_password" id="editPassword"><br>
                Email: <input type="text" name="edit_email" id="editEmail"><br>
                Username: <input type="text" name="edit_username" id="editUsername"><br>
                <input type="submit" value="Save Changes">
            </form>
        </div>
    </div>
    <div class="container" id="passwordsContainer">
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

            $email = $_SESSION['email'];
            $sql = "SELECT id FROM users WHERE email='$email'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $userID = $row['id'];

            $sql = "SELECT PasID, Website, Email, Username, pass FROM Passwordstore WHERE UserID='$userID'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div class='password-entry' data-id='{$row['PasID']}'>";
                    echo "Website: <span class='website'>" . $row["Website"]. "</span> - Email: <span class='email'>" . $row["Email"]. "</span> - Username: <span class='username'>" . $row["Username"] . "</span>";
                    echo "<span class='password-hidden'> - Password: ********</span>";
                    echo "<span class='password-visible' style='display:none'> - Password: " . $row["pass"]. "</span>";
                    echo "<button class='edit-button' onclick='editEntry(" . $row["PasID"] . ")'>Edit</button>";
                    echo "<button class='delete-button' onclick='deleteEntry(" . $row["PasID"] . ")'>Delete</button>";
                    echo "</div>";
                }
            } else {
                echo "0 results";
            }

            $conn->close();
        ?>
    </div>

    <script src="script.js"></script><!-- Script is responsible for not showing password instantly when on site-->
    
    <script>
        function togglePasswordInput() {
            var passwordInput = document.getElementById('password');
            var generateCheckbox = document.getElementById('generatePassword');
            var passwordLengthInput = document.getElementById('passwordLength');
            
            if (generateCheckbox.checked) {
                passwordInput.type = 'text';
                passwordInput.value = generateRandomPassword(passwordLengthInput.value);
            } else {
                passwordInput.type = 'password';
                passwordInput.value = '';
            }
        }

        function generateRandomPassword(length) {
            var characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+";
            var password = "";
            for (var i = 0; i < length; i++) {
                password += characters.charAt(Math.floor(Math.random() * characters.length));
            }
            return password;
        }

        function editEntry(id) {
            var editModal = document.getElementById('editModal');
            var closeEditButton = document.getElementById('closeEditButton');
            var editWebsite = document.getElementById('editWebsite');
            var editPassword = document.getElementById('editPassword');
            var editEmail = document.getElementById('editEmail');
            var editUsername = document.getElementById('editUsername');
            var editEntryID = document.getElementById('editEntryID');

            var website = document.querySelector(`[data-id="${id}"] .website`).textContent;
            var email2 = document.querySelector(`[data-id="${id}"] .email`).textContent;
            var username = document.querySelector(`[data-id="${id}"] .username`).textContent;
            var passwordValue = document.querySelector(`.password-entry[data-id='${id}'] .password-visible`).textContent.trim();

            editWebsite.value = website;
            editEmail.value = email2;
            editUsername.value = username;
            editEntryID.value = id;

            // Set the password field value
            editPassword.type = 'text'; // Change the input type to text
            editPassword.value = passwordValue;

            // Remove the label from the input field
            editPassword.setAttribute('placeholder', 'Password');

            editModal.style.display = 'block';

            closeEditButton.onclick = function() {
                editModal.style.display = 'none';
            }

            window.onclick = function(event) {
                if (event.target == editModal) {
                    editModal.style.display = 'none';
                }
            }
        }



        function deleteEntry(id) {
            if (confirm("Are you sure you want to delete this entry?")) {
                window.location.href = 'delete_process.php?id=' + id;
            }
        }
        document.getElementById('logoutButton').addEventListener('click', function() {
            window.location.href = 'logout.php';
        });
    </script>
</body>
</html>
