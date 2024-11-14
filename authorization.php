<?php
// authorization.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authorization</title>
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

<h1>Login</h1>
<div class="centered-form">
    <form id="auth" action="php/signup.php" method="POST" onsubmit="return validateForm();">
        <label for="userid">Username:</label>
        <input type="text" id="userid" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="userpass" required>

        <button type="submit" id="btnreg" name="signin">Login</button>
        <a href="registration.php" class="small-link">Don't have an account? Register</a>
    </form>
</div>

<script>
function validateForm() {
    var username = document.getElementById('userid').value;
    if (!/^[A-Za-zА-Яа-я]/.test(username)) {
        alert('Username must start with a letter.');
        return false;
    }
    return true;
}
</script>

</body>
</html>
