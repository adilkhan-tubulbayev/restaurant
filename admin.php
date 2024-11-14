<?php
//admin.php
session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    echo "<script>alert('Access denied. Please log in as an admin.');</script>";
    echo "<script>document.location.href='authorization.php';</script>";
    exit();
}

$host = "localhost";
$user = "nis";
$pass = "!nis2024";
$dbname = "restaurant";

$connect = mysqli_connect($host, $user, $pass, $dbname);
if (!$connect) {
    die("Something went wrong: " . mysqli_connect_error());
}

// Обработка формы добавления меню
if (isset($_POST['add_menu'])) {
    $mname = mysqli_real_escape_string($connect, $_POST['mname']);
    $mdescription = mysqli_real_escape_string($connect, $_POST['mdescription']);
    $mprice = mysqli_real_escape_string($connect, $_POST['mprice']);

    if (isset($_FILES['mdishes']) && $_FILES['mdishes']['error'] == 0) {
        $mdishes = $_FILES['mdishes']['name'];
        $file_type = strtolower(pathinfo($mdishes, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (!in_array($file_type, $allowed_types)) {
            echo "<script>alert('Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.');</script>";
            echo "<script>document.location.href='admin.php';</script>";
            exit();
        }

        $unique_mdishes = uniqid() . '.' . $file_type;
        $target_dir = "uploads/";
        $target_file = $target_dir . $unique_mdishes;

        if (move_uploaded_file($_FILES['mdishes']['tmp_name'], $target_file)) {
            $stmt = $connect->prepare("INSERT INTO menu (mdishes, mname, mdescription, mprice) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $unique_mdishes, $mname, $mdescription, $mprice);

            if ($stmt->execute()) {
                echo "<script>alert('Dish added successfully!');</script>";
                echo "<script>document.location.href='admin.php';</script>";
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "<script>alert('Failed to upload image.');</script>";
        }
    } else {
        echo "<script>alert('Please select an image to upload.');</script>";
        echo "<script>document.location.href='admin.php';</script>";
        exit();
    }
}

// Обработка удаления меню
if (isset($_GET['delete_menu'])) {
    $mid = mysqli_real_escape_string($connect, $_GET['delete_menu']);
    
    $result = mysqli_query($connect, "SELECT mdishes FROM menu WHERE mid = '$mid'");
    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $mdishes = $row['mdishes'];
        $file_path = "uploads/" . $mdishes;

        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }

    $stmt = $connect->prepare("DELETE FROM menu WHERE mid = ?");
    $stmt->bind_param("i", $mid);
    if ($stmt->execute()) {
        echo "<script>alert('Menu item deleted successfully.');</script>";
        echo "<script>document.location.href='admin.php';</script>";
        exit();
    } else {
        echo "Error deleting record: " . $stmt->error;
    }
    $stmt->close();
}

// Обработка удаления бронирования
if (isset($_GET['delete_booking'])) {
    $bid = mysqli_real_escape_string($connect, $_GET['delete_booking']);
    $stmt = $connect->prepare("DELETE FROM booking WHERE bid = ?");
    $stmt->bind_param("i", $bid);
    if ($stmt->execute()) {
        echo "<script>alert('Booking deleted successfully.');</script>";
        echo "<script>document.location.href='admin.php';</script>";
        exit();
    } else {
        echo "Error deleting booking: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
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
        <?php if (isset($_SESSION['username'])) { echo '<li><a href="php/logout.php">Logout</a></li>'; } ?>
    </ol>
</div>

<h1 class="page-title">Admin Panel</h1>

<div class="admin-panel-container">
    <!-- Booking Management -->
    <div class="admin-card">
        <h2>Booking Management</h2>
        <table class="admin-table">
            <tr>
                <th>Booking ID</th>
                <th>User ID</th>
                <th>Name</th>
                <th>Guests</th>
                <th>Table</th>
                <th>Time</th>
                <th>Actions</th>
            </tr>
            <?php
            $result = mysqli_query($connect, "SELECT * FROM booking");
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['bid']) . "</td>";
                echo "<td>" . htmlspecialchars($row['uid']) . "</td>";
                echo "<td>" . htmlspecialchars($row['bname']) . "</td>";
                echo "<td>" . htmlspecialchars($row['bguests']) . "</td>";
                echo "<td>" . htmlspecialchars($row['btable']) . "</td>";
                echo "<td>" . htmlspecialchars($row['btime']) . "</td>";
                echo "<td><a href='edit_booking.php?bid=" . htmlspecialchars($row['bid']) . "'>Edit</a> | <a href='admin.php?delete_booking=" . htmlspecialchars($row['bid']) . "'>Delete</a></td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>

    <!-- Add New Menu Item -->
    <div class="admin-card">
        <h2>Add New Menu Item</h2>
        <form action="admin.php" method="POST" enctype="multipart/form-data">
            <label for="mdishes">Dish Image:</label>
            <input type="file" id="mdishes" name="mdishes" required><br>
            <label for="mname">Dish Name:</label>
            <input type="text" id="mname" name="mname" required><br>
            <label for="mdescription">Description:</label>
            <textarea id="mdescription" name="mdescription" required></textarea><br>
            <label for="mprice">Price:</label>
            <input type="text" id="mprice" name="mprice" required><br>
            <button type="submit" name="add_menu">Add to Menu</button>
        </form>
    </div>

    <!-- Current Menu Items -->
    <div class="admin-card">
        <h2>Current Menu Items</h2>
        <table class="admin-table">
            <tr>
                <th>Menu ID</th>
                <th>Dish Image</th>
                <th>Dish Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
            <?php
            $result = mysqli_query($connect, "SELECT * FROM menu");
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['mid']) . "</td>";
                echo "<td><img src='uploads/" . htmlspecialchars($row['mdishes']) . "' width='80' height='80'></td>";
                echo "<td>" . htmlspecialchars($row['mname']) . "</td>";
                echo "<td>" . htmlspecialchars($row['mdescription']) . "</td>";
                echo "<td>$" . htmlspecialchars($row['mprice']) . "</td>";
                echo "<td><a href='edit_menu.php?mid=" . htmlspecialchars($row['mid']) . "'>Edit</a> | <a href='admin.php?delete_menu=" . htmlspecialchars($row['mid']) . "'>Delete</a></td>";
                echo "</tr>";
            }
            mysqli_close($connect);
            ?>
        </table>
    </div>
</div>
</body>
</html>