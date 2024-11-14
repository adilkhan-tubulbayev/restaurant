<?php
// signup.php
session_start();

$host = "localhost";
$user = "nis";
$pass = "!nis2024";
$dbname = "restaurant";

$connect = mysqli_connect($host, $user, $pass, $dbname);

if (!$connect) {
    die("Что-то пошло не так: " . mysqli_connect_error());
}

if (isset($_POST['signup'])) {
    $username = mysqli_real_escape_string($connect, $_POST['username']);
    $userpass = mysqli_real_escape_string($connect, $_POST['userpass']);
    $confirmPassword = mysqli_real_escape_string($connect, $_POST['confirm_password']);

    // Проверка, что имя пользователя начинается с буквы
    if (!preg_match('/^[A-Za-zА-Яа-я]/u', $username)) {
        echo "<script>alert('Имя пользователя должно начинаться с буквы. Пожалуйста, попробуйте снова.');</script>";
        echo "<script>document.location.href='../registration.php';</script>";
        exit;
    }

    if (strlen($userpass) < 8) {
        echo "<script>alert('Пароль должен состоять минимум из 8 символов. Попробуйте снова.');</script>";
        echo "<script>document.location.href='../registration.php';</script>";
        exit;
    }

    if ($userpass !== $confirmPassword) {
        echo "<script>alert('Пароли не совпадают. Пожалуйста, попробуйте снова.');</script>";
        echo "<script>document.location.href='../registration.php';</script>";
        exit;
    }

    // Проверка, существует ли уже такое имя пользователя
    $result = mysqli_query($connect, "SELECT * FROM users WHERE ulogin = '$username'");
    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Имя пользователя уже существует. Пожалуйста, выберите другое имя.');</script>";
        echo "<script>document.location.href='../registration.php';</script>";
        exit;
    }

    // Регистрация пользователя
    $sql = "INSERT INTO users (ulogin, upassword) VALUES ('$username', '$userpass')";
    if (mysqli_query($connect, $sql)) {
        echo "<script>alert('Вы успешно зарегистрированы!');</script>";
        echo "<script>document.location.href='../index.php';</script>";
    } else {
        echo "Ошибка: " . $sql . "<br>" . mysqli_error($connect);
    }
}

if (isset($_POST['signin'])) {
    $username = mysqli_real_escape_string($connect, $_POST['username']);
    $userpass = mysqli_real_escape_string($connect, $_POST['userpass']);

    // Проверка, что имя пользователя начинается с буквы
    if (!preg_match('/^[A-Za-zА-Яа-я]/u', $username)) {
        echo "<script>alert('Имя пользователя должно начинаться с буквы.');</script>";
        echo "<script>document.location.href='../authorization.php';</script>";
        exit;
    }

    $sel = mysqli_query($connect, "SELECT * FROM users WHERE ulogin = '$username'");

    if (!$sel) {
        echo "Что-то пошло не так: " . mysqli_error($connect);
    } else {
        $data = mysqli_fetch_array($sel);
        if ($data) {
            if ($data['upassword'] === $userpass) {
                $_SESSION['username'] = $username;
                $_SESSION['uid'] = $data['uid'];
                // Добавляем алерт "Добро пожаловать!" и перенаправление
                echo "<script>alert('Добро пожаловать!'); window.location.href='../admin.php';</script>";
                exit();
            } else {
                echo "<script>alert('Неправильный пароль.');</script>";
                echo "<script>document.location.href='../authorization.php';</script>";
            }
        } else {
            echo "<script>alert('Неправильное имя пользователя.');</script>";
            echo "<script>document.location.href='../authorization.php';</script>";
        }
    }
}

mysqli_close($connect);
?>
