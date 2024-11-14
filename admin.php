<?php
session_start();

// Проверка, авторизован ли пользователь как администратор
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
            echo "<script>alert('Неверный тип файла. Разрешены JPG, JPEG, PNG и GIF.');</script>";
            echo "<script>document.location.href='admin.php';</script>";
            exit();
        }

        // Генерация уникального имени файла
        $unique_mdishes = uniqid() . '.' . $file_type;
        $target_dir = "uploads/";
        $target_file = $target_dir . $unique_mdishes;

        if (move_uploaded_file($_FILES['mdishes']['tmp_name'], $target_file)) {
            $stmt = $connect->prepare("INSERT INTO menu (mdishes, mname, mdescription, mprice) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $unique_mdishes, $mname, $mdescription, $mprice);

            if ($stmt->execute()) {
                echo "<script>alert('Блюдо успешно добавлено!');</script>";
                echo "<script>document.location.href='admin.php';</script>";
                exit();
            } else {
                echo "Ошибка: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "<script>alert('Не удалось загрузить изображение.');</script>";
        }
    } else {
        echo "<script>alert('Пожалуйста, выберите изображение для загрузки.');</script>";
        echo "<script>document.location.href='admin.php';</script>";
        exit();
    }
}

// Обработка удаления пункта меню
if (isset($_GET['delete_menu'])) {
    $mid = mysqli_real_escape_string($connect, $_GET['delete_menu']);
    
    // Получение имени файла для удаления
    $result = mysqli_query($connect, "SELECT mdishes FROM menu WHERE mid = '$mid'");
    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $mdishes = $row['mdishes'];
        $target_dir = "uploads/";
        $file_path = $target_dir . $mdishes;

        // Удаление файла изображения, если он существует
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }

    $stmt = $connect->prepare("DELETE FROM menu WHERE mid = ?");
    $stmt->bind_param("i", $mid);
    if ($stmt->execute()) {
        echo "<script>alert('Пункт меню успешно удалён.');</script>";
        echo "<script>document.location.href='admin.php';</script>";
        exit();
    } else {
        echo "Ошибка удаления записи: " . $stmt->error;
    }

    $stmt->close();
}

// Обработка удаления бронирования
if (isset($_GET['delete_booking'])) {
    $bid = mysqli_real_escape_string($connect, $_GET['delete_booking']);
    $stmt = $connect->prepare("DELETE FROM booking WHERE bid = ?");
    $stmt->bind_param("i", $bid);
    if ($stmt->execute()) {
        echo "<script>alert('Бронирование успешно удалено.');</script>";
        echo "<script>document.location.href='admin.php';</script>";
        exit();
    } else {
        echo "Ошибка удаления бронирования: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Панель администратора</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <h1>Панель администратора</h1>
    <div id="sidebar">
        <h2>Управление бронированиями</h2>
        <table border="1">
            <tr>
                <th>ID бронирования</th>
                <th>ID пользователя</th>
                <th>ID администратора</th>
                <th>Имя</th>
                <th>Количество гостей</th>
                <th>Номер столика</th>
                <th>Время бронирования</th>
                <th>Телефон</th>
                <th>Действия</th>
            </tr>
            <?php
            $result = mysqli_query($connect, "SELECT * FROM booking");
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['bid']) . "</td>";
                echo "<td>" . htmlspecialchars($row['uid']) . "</td>";
                echo "<td>" . htmlspecialchars($row['aid']) . "</td>";
                echo "<td>" . htmlspecialchars($row['bname']) . "</td>";
                echo "<td>" . htmlspecialchars($row['bguests']) . "</td>";
                echo "<td>" . htmlspecialchars($row['btable']) . "</td>";
                echo "<td>" . htmlspecialchars($row['btime']) . "</td>";
                echo "<td>" . htmlspecialchars($row['bphone']) . "</td>";
                echo "<td><a href='edit_booking.php?bid=" . htmlspecialchars($row['bid']) . "'>Редактировать</a> | <a href='admin.php?delete_booking=" . htmlspecialchars($row['bid']) . "'>Удалить</a></td>";
                echo "</tr>";
            }
            ?>
        </table>

        <h2>Управление меню</h2>
        <form action="admin.php" method="POST" enctype="multipart/form-data">
            <label for="mdishes">Изображение блюда:</label>
            <input type="file" id="mdishes" name="mdishes" required><br>
            <label for="mname">Название блюда:</label>
            <input type="text" id="mname" name="mname" required><br>
            <label for="mdescription">Описание:</label>
            <textarea id="mdescription" name="mdescription" required></textarea><br>
            <label for="mprice">Цена:</label>
            <input type="text" id="mprice" name="mprice" required><br>
            <button type="submit" name="add_menu">Добавить в меню</button>
        </form>

        <h2>Текущие пункты меню</h2>
        <table border="1">
            <tr>
                <th>ID меню</th>
                <th>ID пользователя</th>
                <th>ID администратора</th>
                <th>Изображение блюда</th>
                <th>Название блюда</th>
                <th>Описание</th>
                <th>Цена</th>
                <th>Действия</th>
            </tr>
            <?php
            $result = mysqli_query($connect, "SELECT * FROM menu");
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['mid']) . "</td>";
                echo "<td>" . htmlspecialchars($row['uid']) . "</td>";
                echo "<td>" . htmlspecialchars($row['aid']) . "</td>";
                echo "<td><img src='uploads/" . htmlspecialchars($row['mdishes']) . "' width='100' height='100' alt='Изображение блюда'></td>";
                echo "<td>" . htmlspecialchars($row['mname']) . "</td>";
                echo "<td>" . htmlspecialchars($row['mdescription']) . "</td>";
                echo "<td>" . htmlspecialchars($row['mprice']) . "</td>";
                echo "<td><a href='edit_menu.php?mid=" . htmlspecialchars($row['mid']) . "'>Редактировать</a> | <a href='admin.php?delete_menu=" . htmlspecialchars($row['mid']) . "'>Удалить</a></td>";
                echo "</tr>";
            }
            mysqli_close($connect);
            ?>
        </table>
    
        <ol>
            <li><a href="index.php">Главная страница</a></li>
            <li><a href="about.php">О нас</a></li>
            <li><a href="menu.php">Меню</a></li>
            <li><a href="booking.php">Бронирование</a></li>
            <li><a href="registration.php">Регистрация</a></li>
            <li><a href="authorization.php">Авторизация</a></li>
            <li><a href="php/logout.php">Выйти</a></li>
        </ol>
    </div>
</body>
</html>
