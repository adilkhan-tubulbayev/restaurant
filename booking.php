<?php
//booking.php
session_start();
if (!isset($_SESSION['username'])) {
    echo "<script>alert('You need to log in to book a table.');</script>";
    echo "<script>document.location.href='authorization.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Table</title>
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
        <li><a href="php/logout.php">Logout</a></li>
    </ol>
</div>

<h1>Booking Table</h1>
<div class="centered-form">
    <form id="booking" action="php/booking.php" method="POST" onsubmit="return validateBookingForm();">
        <label for="bname">Full Name:</label>
        <input type="text" id="bname" name="bname" required>

        <label for="bguests">Number of Guests:</label>
        <input type="number" id="bguests" name="bguests" required>

        <label for="btable">Table Number:</label>
        <input type="number" id="btable" name="btable" required>

        <label for="btime">Booking Time:</label>
        <input type="time" id="btime" name="btime" required>

        <label for="bphone">Phone Number:</label>
        <input type="text" id="bphone" name="bphone" required>

        <button type="submit" id="btnBooking" name="bookTable">Book Now</button>
    </form>
</div>

<script>
function validateBookingForm() {
    var name = document.getElementById('bname').value;
    if (!/^[A-Za-zА-Яа-я]/.test(name)) {
        alert('Name must start with a letter.');
        return false;
    }
    return true;
}
</script>

</body>
</html>
