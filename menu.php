<?php
// menu.php
session_start();
$connect = mysqli_connect("localhost", "nis", "!nis2024", "restaurant");
if (!$connect) {
    die("Something went wrong: " . mysqli_connect_error());
}

$result = mysqli_query($connect, "SELECT * FROM menu");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - King's Landing</title>
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


<!-- Menu Items -->
<h1>Our Menu</h1>
<div id="menu_items">
    <?php
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='menu_item'>";
        echo "<img src='uploads/" . htmlspecialchars($row['mdishes']) . "' alt='Dish Image'>";
        echo "<h3>" . htmlspecialchars($row['mname']) . "</h3>";
        echo "<p>" . htmlspecialchars($row['mdescription']) . "</p>";
        echo "<p class='price'>Price: $" . htmlspecialchars($row['mprice']) . "</p>";
        echo "</div>";
    }
    ?>
</div>

</body>
</html>
<?php mysqli_close($connect); ?>
