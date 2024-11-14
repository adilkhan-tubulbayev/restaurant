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
<div class="about-container">
    <!-- Image Card -->
    <div class="about-image">
        <img src="img/restaurant-zone.png" alt="Restaurant Zone" class="restaurant-image">
				<img src="img/restaurant-kitchen.png" alt="Restaurant Kitchen" class="restaurant-image">
				<img src="img/restaurant-zone-2.png" alt="Restaurant Zone 2" class="restaurant-image">
    </div>
    
    <!-- Description Card -->
    <div class="about-description">
        <h1>About King's Landing</h1>
        <h2>Our Mission</h2>
        <p>At King's Landing, our mission is to provide an unforgettable dining experience that combines exquisite flavors with a warm and welcoming ambiance. We believe that dining should be more than just a meal – it should be an experience that tantalizes all your senses.</p>
        
        <h2>Our Story</h2>
        <p>Founded in 2024, King's Landing was inspired by the beauty of community gatherings and the power of shared meals. Over the years, we have grown to be a beloved establishment, known for our culinary expertise and dedication to quality. Our chefs bring their passion to every dish, creating flavors that celebrate local ingredients and timeless recipes.</p>

        <h2>Our Team</h2>
        <p>Our team of talented chefs and staff is committed to excellence in every aspect of service. From the kitchen to the dining floor, every member plays a vital role in ensuring that each guest feels valued and appreciated. We look forward to welcoming you to our table and sharing our love for food and hospitality.</p>
        
        <h2>Join Us</h2>
        <p>Whether you are celebrating a special occasion or just looking for a great meal, we invite you to join us at King's Landing. Come savor the flavors of our unique dishes, crafted with love and served with pride. We look forward to seeing you soon!</p>
    </div>
</div>

</body>
</html>
