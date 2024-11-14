<?php
// about.php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About King's Landing</title>
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
        <?php 
            if (isset($_SESSION['username'])) { 
                if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) { 
                    echo '<li><a href="admin.php">Admin Panel</a></li>';
                }
                echo '<li><a href="php/logout.php">Logout</a></li>';
            }
        ?>
    </ol>
</div>


<!-- Page Content -->
<h1>About King's Landing</h1>
<div class="centered-form">
    <p>Welcome to King's Landing! Our restaurant brings you the finest dining experience.</p>
</div>

</body>
</html>
