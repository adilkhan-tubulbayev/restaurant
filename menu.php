<?php
// menu.php
session_start();
$connect = mysqli_connect("localhost", "nis", "!nis2024", "restaurant");
if (!$connect) {
    die("Something went wrong: " . mysqli_connect_error());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Menu - King's Landing</title>
    <link rel="stylesheet" href="css/main.css">
    <style>
        /* Стилизация поисковой формы */
        .search-form {
            text-align: center;
            margin-bottom: 20px;
        }
        .search-input {
            width: 50%;
            max-width: 500px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px 0 0 4px;
            box-sizing: border-box;
            outline: none;
        }
        .search-button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #b76e79;
            color: #fff;
            border: none;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .search-button:hover {
            background-color: #a45259;
        }
        /* Адаптивность для мобильных устройств */
        @media (max-width: 600px) {
            .search-input {
                width: 70%;
            }
        }
    </style>
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

<!-- Search Form -->
<div class="search-form">
    <input type="text" id="search" class="search-input" placeholder="Search for dishes...">
</div>

<div id="menu_items">
    <!-- Здесь будут загружаться блюда -->
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function fetchDishes(query = '') {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'fetch_dishes.php?search=' + encodeURIComponent(query), true);
        xhr.onload = function() {
            if (this.status === 200) {
                document.getElementById('menu_items').innerHTML = this.responseText;
            }
        };
        xhr.send();
    }

    // Изначально загружаем все блюда
    fetchDishes();

    // Добавляем обработчик события ввода для поля поиска
    document.getElementById('search').addEventListener('input', function() {
        const query = this.value;
        fetchDishes(query);
    });
});
</script>

</body>
</html>
