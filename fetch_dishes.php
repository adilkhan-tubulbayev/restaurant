<?php
// fetch_dishes.php
session_start();
$connect = mysqli_connect("localhost", "nis", "!nis2024", "restaurant");
if (!$connect) {
    die("Something went wrong: " . mysqli_connect_error());
}

$search_query = "";
if (isset($_GET['search'])) {
    $search_query = mysqli_real_escape_string($connect, $_GET['search']);
    $result = mysqli_query($connect, "SELECT * FROM menu WHERE mname LIKE '%$search_query%'");
} else {
    $result = mysqli_query($connect, "SELECT * FROM menu");
}

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='menu_item'>";
        echo "<img src='uploads/" . htmlspecialchars($row['mdishes']) . "' alt='Dish Image'>";
        echo "<h3>" . htmlspecialchars($row['mname']) . "</h3>";
        echo "<p>" . htmlspecialchars($row['mdescription']) . "</p>";
        echo "<p class='price'>Price: $" . htmlspecialchars($row['mprice']) . "</p>";
        echo "</div>";
    }
} else {
    echo "<p style='text-align:center;'>No dishes found matching your search.</p>";
}
mysqli_close($connect);
?>
