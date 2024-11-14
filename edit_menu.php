<?php
// edit_menu.php
session_start();

// Проверка, авторизован ли пользователь
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Доступ запрещён. Пожалуйста, авторизуйтесь.');</script>";
    echo "<script>document.location.href='authorization.php';</script>";
    exit();
}

// Подключение к базе данных
$host = "localhost";
$user = "nis";
$pass = "!nis2024";
$dbname = "restaurant";

$connect = mysqli_connect($host, $user, $pass, $dbname);
if (!$connect) {
    die("Что-то пошло не так: " . mysqli_connect_error());
}

// Обработка формы редактирования
if (isset($_POST['update_menu'])) {
    $mid = mysqli_real_escape_string($connect, $_POST['mid']);
    $mname = mysqli_real_escape_string($connect, $_POST['mname']);
    $mdescription = mysqli_real_escape_string($connect, $_POST['mdescription']);
    $mprice = mysqli_real_escape_string($connect, $_POST['mprice']);

    // Сохранение текущего изображения
    $current_mdishes = mysqli_real_escape_string($connect, $_POST['current_mdishes']);

    // Обработка загрузки нового изображения, если оно предоставлено
    if (isset($_FILES['mdishes']) && $_FILES['mdishes']['error'] == 0) {
        $new_mdishes = $_FILES['mdishes']['name'];
        $file_type = strtolower(pathinfo($new_mdishes, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($file_type, $allowed_types)) {
            echo "<script>alert('Неверный тип файла. Разрешены JPG, JPEG, PNG и GIF.');</script>";
            echo "<script>window.history.back();</script>";
            exit();
        }

        // Генерация уникального имени файла, чтобы избежать конфликтов
        $unique_mdishes = uniqid() . '.' . $file_type;
        $target_dir = "uploads/";
        $target_file = $target_dir . $unique_mdishes;

        if (move_uploaded_file($_FILES['mdishes']['tmp_name'], $target_file)) {
            // Удаление старого изображения, если оно существует
            if (!empty($current_mdishes) && file_exists($target_dir . $current_mdishes)) {
                unlink($target_dir . $current_mdishes);
            }
            $mdishes = $unique_mdishes;
        } else {
            echo "<script>alert('Не удалось загрузить изображение.');</script>";
            echo "<script>window.history.back();</script>";
            exit();
        }
    } else {
        // Если новое изображение не загружено, сохраняем текущее
        $mdishes = $current_mdishes;
    }

    // Использование подготовленных выражений для безопасности
    $stmt = $connect->prepare("UPDATE menu SET mname = ?, mdescription = ?, mprice = ?, mdishes = ? WHERE mid = ?");
    $stmt->bind_param("ssssi", $mname, $mdescription, $mprice, $mdishes, $mid);

    if ($stmt->execute()) {
        echo "<script>alert('Пункт меню успешно обновлён!');</script>";
        echo "<script>document.location.href='admin.php';</script>";
        exit();
    } else {
        echo "Ошибка обновления записи: " . $stmt->error;
    }

    $stmt->close();
}

// Получение данных текущего пункта меню для редактирования
if (isset($_GET['mid'])) {
    $mid = mysqli_real_escape_string($connect, $_GET['mid']);
    $result = mysqli_query($connect, "SELECT * FROM menu WHERE mid = '$mid'");
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
    } else {
        echo "<script>alert('Пункт меню не найден.');</script>";
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
    <title>Редактировать пункт меню</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <h1>Редактировать пункт меню</h1>
    <div id="sidebar">
        <ol>
            <li><a href="admin.php">Панель администратора</a></li>
            <li><a href="index.php">Главная страница</a></li>
            <li><a href="about.php">О нас</a></li>
            <li><a href="menu.php">Меню</a></li>
            <li><a href="booking.php">Бронирование</a></li>
            <li><a href="registration.php">Регистрация</a></li>
            <li><a href="authorization.php">Авторизация</a></li>
            <li><a href="php/logout.php">Выйти</a></li>
        </ol>
    </div>
    <div id="form">
        <form action="edit_menu.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="mid" value="<?php echo $row['mid']; ?>">
            <input type="hidden" name="current_mdishes" value="<?php echo htmlspecialchars($row['mdishes']); ?>">

            <label for="mdishes">Изображение блюда:</label>
            <input type="file" id="mdishes" name="mdishes"><br>
            <img src="uploads/<?php echo htmlspecialchars($row['mdishes']); ?>" width="100" height="100" alt="Текущее изображение"><br>

            <label for="mname">Название блюда:</label>
            <input type="text" id="mname" name="mname" value="<?php echo htmlspecialchars($row['mname']); ?>" required><br>

            <label for="mdescription">Описание:</label>
            <textarea id="mdescription" name="mdescription" required><?php echo htmlspecialchars($row['mdescription']); ?></textarea><br>

            <label for="mprice">Цена:</label>
            <input type="text" id="mprice" name="mprice" value="<?php echo htmlspecialchars($row['mprice']); ?>" required><br>

            <button type="submit" name="update_menu">Обновить пункт меню</button>
        </form>
        <a href="admin.php">Вернуться в панель администратора</a>
    </div>
</body>
</html>
