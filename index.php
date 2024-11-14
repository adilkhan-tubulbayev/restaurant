<?php
// index.php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>King's Landing</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <h1>King's Landing</h1>
    <div id="sidebar">
        <ol>
            <li><a href="index.php">Main Page</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="menu.php">Menu</a></li>
            <li><a href="booking.php">Booking</a></li>
            <li><a href="registration.php">Registration</a></li>
            <li><a href="authorization.php">Authorization</a></li>
            <?php if (isset($_SESSION['username'])) { echo '<li><a href="admin.php">Admin Panel</a></li>'; echo '<li><a href="php/logout.php">Logout</a></li>'; } ?>
        </ol>
    </div>
    <div id="user">
        <?php
        if (isset($_SESSION['username'])) {
            echo "Welcome, " . $_SESSION['username'];
        } else {
            echo "Welcome, Guest";
        }
        ?>
    </div>
</body>
</html>