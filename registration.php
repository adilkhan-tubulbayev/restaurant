<?php
// registration.php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <h1>Регистрация</h1>
    <div id="sidebar">
        <ol>
            <li><a href="index.php">Главная страница</a></li>
            <li><a href="about.php">О нас</a></li>
            <li><a href="menu.php">Меню</a></li>
            <li><a href="booking.php">Бронирование</a></li>
            <li><a href="#">Регистрация</a></li>
            <li><a href="authorization.php">Авторизация</a></li>
            <?php if (isset($_SESSION['username'])) { echo '<li><a href="admin.php">Панель администратора</a></li>'; echo '<li><a href="php/logout.php">Выйти</a></li>'; } ?>
        </ol>
    </div>
    <div id="form">
        <form id="reg" action="php/signup.php" method="POST" onsubmit="return validateForm();">
            <label for="userid">Имя пользователя:</label>
            <input type="text" id="userid" name="username" required>
            <br>
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="userpass" required>
            <br>
            <label for="confirm_password">Подтвердите пароль:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <br>
            <button id="btnreg" value="Регистрация" name="signup">Зарегистрироваться</button>
            <a href="authorization.php"> Уже есть аккаунт? Войти </a>
        </form>
    </div>

    <script>
    function validateForm() {
        var username = document.getElementById('userid').value;
        var password = document.getElementById('password').value;
        var confirmPassword = document.getElementById('confirm_password').value;

        if (!/^[A-Za-zА-Яа-я]/.test(username)) {
            alert('Имя пользователя должно начинаться с буквы.');
            return false;
        }
        if (password.length < 8) {
            alert('Пароль должен состоять минимум из 8 символов.');
            return false;
        }
        if (password !== confirmPassword) {
            alert('Пароли не совпадают.');
            return false;
        }
        return true;
    }
    </script>
</body>
</html>
