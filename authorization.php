<?php
// authorization.php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <h1>Авторизация</h1>
    <div id="sidebar">
        <ol>
            <li><a href="index.php">Главная страница</a></li>
            <li><a href="about.php">О нас</a></li>
            <li><a href="menu.php">Меню</a></li>
            <li><a href="booking.php">Бронирование</a></li>
            <li><a href="registration.php">Регистрация</a></li>
            <li><a href="#">Авторизация</a></li>
            <?php if (isset($_SESSION['username'])) { echo '<li><a href="admin.php">Панель администратора</a></li>'; echo '<li><a href="php/logout.php">Выйти</a></li>'; } ?>
        </ol>
    </div>
    <div id="right_sidebar">
        <form id="reg" action="php/signup.php" method="POST" onsubmit="return validateForm();">
            <label for="userid">Имя пользователя:</label>
            <input type="text" id="userid" name="username" required>
            <br>
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="userpass" required>
            <br>
            <button id="btnreg" value="Войти" name="signin">Войти</button>
            <a href="registration.php"> Нет аккаунта? Зарегистрируйтесь </a>
        </form>
    </div>

    <script>
    function validateForm() {
        var username = document.getElementById('userid').value;
        if (!/^[A-Za-zА-Яа-я]/.test(username)) {
            alert('Имя пользователя должно начинаться с буквы.');
            return false;
        }
        return true;
    }
    </script>
</body>
</html>
