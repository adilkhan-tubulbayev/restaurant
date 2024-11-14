<?php
// edit_booking.php
session_start();

if (!isset($_SESSION['username'])) {
    echo "<script>alert('Доступ запрещён. Пожалуйста, авторизуйтесь.');</script>";
    echo "<script>document.location.href='authorization.php';</script>";
    exit();
}

$host = "localhost";
$user = "nis";
$pass = "!nis2024";
$dbname = "restaurant";

$connect = mysqli_connect($host, $user, $pass, $dbname);
if (!$connect) {
    die("Что-то пошло не так: " . mysqli_connect_error());
}

if (isset($_POST['update_booking'])) {
    $bid = mysqli_real_escape_string($connect, $_POST['bid']);
    $uid = mysqli_real_escape_string($connect, $_POST['uid']);
    $bname = mysqli_real_escape_string($connect, $_POST['bname']);
    $bguests = mysqli_real_escape_string($connect, $_POST['bguests']);
    $btable = mysqli_real_escape_string($connect, $_POST['btable']);
    $btime = mysqli_real_escape_string($connect, $_POST['btime']);
    $bphone = mysqli_real_escape_string($connect, $_POST['bphone']);

    $stmt = $connect->prepare("UPDATE booking SET uid = ?, bname = ?, bguests = ?, btable = ?, btime = ?, bphone = ? WHERE bid = ?");
    $stmt->bind_param("isiissi", $uid, $bname, $bguests, $btable, $btime, $bphone, $bid);

    if ($stmt->execute()) {
        echo "<script>alert('Бронирование успешно обновлено!');</script>";
        echo "<script>document.location.href='admin.php';</script>";
        exit();
    } else {
        echo "Ошибка обновления записи: " . $stmt->error;
    }

    $stmt->close();
}

if (isset($_GET['bid'])) {
    $bid = mysqli_real_escape_string($connect, $_GET['bid']);
    $result = mysqli_query($connect, "SELECT * FROM booking WHERE bid = '$bid'");
    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
    } else {
        echo "<script>alert('Бронирование не найдено.');</script>";
        echo "<script>document.location.href='admin.php';</script>";
        exit();
    }
} else {
    echo "<script>document.location.href='admin.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать бронирование</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>

<!-- Navigation Bar -->
<div class="navbar">
    <ol>
        <li><a href="index.php">Главная страница</a></li>
        <li><a href="about.php">О нас</a></li>
        <li><a href="menu.php">Меню</a></li>
        <li><a href="booking.php">Бронирование</a></li>
        <li><a href="registration.php">Регистрация</a></li>
        <li><a href="authorization.php">Авторизация</a></li>
        <?php if (isset($_SESSION['username'])) { echo '<li><a href="admin.php">Панель администратора</a></li>'; echo '<li><a href="php/logout.php">Выйти</a></li>'; } ?>
    </ol>
</div>

<h1>Редактировать бронирование</h1>
<div id="form">
    <form action="edit_booking.php" method="POST">
        <input type="hidden" name="bid" value="<?php echo htmlspecialchars($row['bid']); ?>">

        <label for="uid">ID пользователя:</label>
        <input type="number" id="uid" name="uid" value="<?php echo htmlspecialchars($row['uid']); ?>" required><br>

        <label for="bname">Имя:</label>
        <input type="text" id="bname" name="bname" value="<?php echo htmlspecialchars($row['bname']); ?>" required><br>

        <label for="bguests">Количество гостей:</label>
        <input type="number" id="bguests" name="bguests" value="<?php echo htmlspecialchars($row['bguests']); ?>" required><br>

        <label for="btable">Номер столика:</label>
        <input type="number" id="btable" name="btable" value="<?php echo htmlspecialchars($row['btable']); ?>" required><br>

        <label for="btime">Время бронирования:</label>
        <input type="datetime-local" id="btime" name="btime" value="<?php echo date('Y-m-d\TH:i', strtotime($row['btime'])); ?>" required><br>

        <label for="bphone">Номер телефона:</label>
        <input type="text" id="bphone" name="bphone" value="<?php echo htmlspecialchars($row['bphone']); ?>" required><br>

        <button type="submit" name="update_booking">Обновить бронирование</button>
    </form>
    <a href="admin.php">Вернуться в панель администратора</a>
</div>
</body>
</html>
