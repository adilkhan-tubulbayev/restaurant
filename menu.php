<?php
// menu.php
echo "<h1>Menu in King's Landing</h1>";
session_start();
$connect = mysqli_connect("localhost", "nis", "!nis2024", "restaurant");
if (!$connect) {
    die("Something went wrong: " . mysqli_connect_error());
}

$result = mysqli_query($connect, "SELECT * FROM menu");

echo "<div id='menu_items'>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<div class='menu_item'>";
    echo "<img src='uploads/" . $row['mdishes'] . "' alt='Dish Image'>";
    echo "<h3>" . $row['mname'] . "</h3>";
    echo "<p>" . $row['mdescription'] . "</p>";
    echo "<p>Price: " . $row['mprice'] . "</p>";
    echo "</div>";
}
echo "</div>";

mysqli_close($connect);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu in King's Landing</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>

    <div id="sidebar">
        <ol>
            <li><a href="index.php">Main Page</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="#">Menu</a></li>
            <li><a href="booking.php">Booking</a></li>
            <li><a href="registration.php">Registration</a></li>
            <li><a href="authorization.php">Authorization</a></li>
            <?php if (isset($_SESSION['username'])) { echo '<li><a href="admin.php">Admin Panel</a></li>'; echo '<li><a href="php/logout.php">Logout</a></li>'; } ?>
        </ol>
    </div>
</body>
</html>