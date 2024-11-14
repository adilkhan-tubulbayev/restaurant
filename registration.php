<?php
//registration.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>

<!-- Navigation Bar -->
<div class="navbar">
    <ol>
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="menu.php">Menu</a></li>
        <li><a href="booking.php">Booking</a></li>
        <li><a href="registration.php">Register</a></li>
        <li><a href="authorization.php">Login</a></li>
        <?php if (isset($_SESSION['username'])) { echo '<li><a href="admin.php">Admin Panel</a></li>'; echo '<li><a href="php/logout.php">Logout</a></li>'; } ?>
    </ol>
</div>

<h1>Register</h1>
<div class="centered-form">
    <form id="reg" action="php/signup.php" method="POST" onsubmit="return validateForm();">
        <label for="userid">Username:</label>
        <input type="text" id="userid" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="userpass" required>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <button type="submit" id="btnreg" name="signup">Register</button>
        <a href="authorization.php" class="small-link">Already have an account? Log in</a>
    </form>
</div>

<script>
function validateForm() {
    var username = document.getElementById('userid').value;
    var password = document.getElementById('password').value;
    var confirmPassword = document.getElementById('confirm_password').value;

    if (!/^[A-Za-zА-Яа-я]/.test(username)) {
        alert('Username must start with a letter.');
        return false;
    }
    if (password.length < 8) {
        alert('Password must be at least 8 characters.');
        return false;
    }
    if (password !== confirmPassword) {
        alert('Passwords do not match.');
        return false;
    }
    return true;
}
</script>

</body>
</html>
