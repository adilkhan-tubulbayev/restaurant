<?php
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

    // Username validation
    if (!preg_match('/^[A-Za-zА-Яа-я]/u', $username)) {
        echo "<script>alert('Имя пользователя должно начинаться с буквы.');</script>";
        echo "<script>document.location.href='../registration.php';</script>";
        exit;
    }

    if (strlen($userpass) < 8) {
        echo "<script>alert('Пароль должен состоять минимум из 8 символов.');</script>";
        echo "<script>document.location.href='../registration.php';</script>";
        exit;
    }

    if ($userpass !== $confirmPassword) {
        echo "<script>alert('Пароли не совпадают.');</script>";
        echo "<script>document.location.href='../registration.php';</script>";
        exit;
    }

    // Check for duplicate username
    $result = mysqli_query($connect, "SELECT * FROM users WHERE ulogin = '$username'");
    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Имя пользователя уже существует.');</script>";
        echo "<script>document.location.href='../registration.php';</script>";
        exit;
    }

    // Register user
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

    // Check users table
    $sel_user = mysqli_query($connect, "SELECT * FROM users WHERE ulogin = '$username'");
    $data_user = mysqli_fetch_array($sel_user);

    // Check admins table
    $sel_admin = mysqli_query($connect, "SELECT * FROM admins WHERE alogin = '$username'");
    $data_admin = mysqli_fetch_array($sel_admin);

    if ($data_admin && $data_admin['apassword'] === $userpass) {
        // Admin login
        $_SESSION['username'] = $username;
        $_SESSION['uid'] = $data_admin['aid'];
        $_SESSION['is_admin'] = true;
        echo "<script>alert('Добро пожаловать, администратор!'); window.location.href='../admin.php';</script>";
        exit();
    } elseif ($data_user && $data_user['upassword'] === $userpass) {
        // User login
        $_SESSION['username'] = $username;
        $_SESSION['uid'] = $data_user['uid'];
        $_SESSION['is_admin'] = false;
        echo "<script>alert('Добро пожаловать!'); window.location.href='../index.php';</script>";
        exit();
    } else {
        echo "<script>alert('Неправильное имя пользователя или пароль.');</script>";
        echo "<script>document.location.href='../authorization.php';</script>";
    }
}

mysqli_close($connect);
?>
