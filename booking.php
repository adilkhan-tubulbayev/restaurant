<?php
// booking.php
session_start();
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
    <h1>Booking Table</h1>
    <div id="sidebar">
        <ol>
            <li><a href="index.php">Main Page</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="menu.php">Menu</a></li>
            <li><a href="#">Booking</a></li>
            <li><a href="registration.php">Registration</a></li>
            <li><a href="authorization.php">Authorization</a></li>
            <?php if (isset($_SESSION['username'])) { echo '<li><a href="admin.php">Admin Panel</a></li>'; echo '<li><a href="php/logout.php">Logout</a></li>'; } ?>
        </ol>
    </div>
    <div id="form">
        <?php
        if (!isset($_SESSION['username'])) {
            echo "<script>alert('You need to log in to book a table.');</script>";
            echo "<script>document.location.href='authorization.php';</script>";
            exit();
        }
        ?>
        <form id="booking" action="php/booking.php" method="POST" onsubmit="return validateBookingForm();">
            <label for="bname">Full Name:</label>
            <input type="text" id="bname" name="bname" required>
            <br>
            <label for="bguests">Number of Guests:</label>
            <input type="number" id="bguests" name="bguests" required>
            <br>
            <label for="btable">Table Number:</label>
            <input type="number" id="btable" name="btable" required>
            <br>
            <label for="btime">Booking Time:</label>
            <input type="time" id="btime" name="btime" required>
            <br>
            <label for="bphone">Phone Number:</label>
            <input type="text" id="bphone" name="bphone" required>
            <br>
            <button id="btnBooking" value="Booking" name="bookTable">Book Now</button>
        </form>
    </div>

		<script>
function validateBookingForm() {
    var name = document.getElementById('bname').value;
    var guests = document.getElementById('bguests').value;
    var table = document.getElementById('btable').value;
    var time = document.getElementById('btime').value;
    
    // Check if name starts with a letter
    if (!/^[A-Za-zА-Яа-я]/.test(name)) {
        alert('Name must start with a letter.');
        return false;
    }
    
    // Check that guests is a positive integer
    if (!/^[1-9]\d*$/.test(guests)) {
        alert('Number of Guests must be a positive integer.');
        return false;
    }
    
    // Check limits for guests
    var maxGuests = 20; // Set the maximum number of guests
    if (parseInt(guests) > maxGuests) {
        alert('Number of Guests cannot exceed ' + maxGuests + '.');
        return false;
    }
    
    // Check that table is a positive integer
    if (!/^[1-9]\d*$/.test(table)) {
        alert('Table Number must be a positive integer.');
        return false;
    }
    
    // Check limits for tables
    var maxTables = 15; // Set the maximum table number
    if (parseInt(table) > maxTables) {
        alert('Table Number cannot exceed ' + maxTables + '.');
        return false;
    }
    
    // Check that time is selected
    if (time === '') {
        alert('Please select a Booking Time.');
        return false;
    }
    
    return true; // If all checks pass, submit the form
		
}
</script>

</body>
</html>