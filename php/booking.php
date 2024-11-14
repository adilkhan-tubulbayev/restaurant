<?php
// booking.php
session_start();

$host = "localhost";
$user = "nis";
$pass = "!nis2024";
$dbname = "restaurant";

$connect = mysqli_connect($host, $user, $pass, $dbname);

if (!$connect) {
    die("Something went wrong: " . mysqli_connect_error());
}

if (isset($_POST['bookTable'])) {
    if (!isset($_SESSION['uid'])) {
        echo "<script>alert('You need to log in to book a table.');</script>";
        echo "<script>document.location.href='../index.php';</script>";
        exit();
    }

    $uid = $_SESSION['uid'];
    $bname = mysqli_real_escape_string($connect, $_POST['bname']);
    $bguests = mysqli_real_escape_string($connect, $_POST['bguests']);
    $btable = mysqli_real_escape_string($connect, $_POST['btable']);
    $btime = mysqli_real_escape_string($connect, $_POST['btime']);
    $bphone = mysqli_real_escape_string($connect, $_POST['bphone']);

    // Вставка данных о бронировании
    $sql = "INSERT INTO booking (uid, bname, bguests, btable, btime, bphone) VALUES ('$uid', '$bname', '$bguests', '$btable', '$btime', '$bphone')";

    if (mysqli_query($connect, $sql)) {
        // Получаем ID бронирования для связи с транзакцией
        $booking_id = mysqli_insert_id($connect);
        $amount = 100; // Примерная сумма
        $status = "Pending";
        
        // Вставка данных о транзакции
        $transaction_sql = "INSERT INTO transactions (reservation_id, username, amount, status) VALUES ('$booking_id', '$uid', '$amount', '$status')";
        if (mysqli_query($connect, $transaction_sql)) {
            $transaction_id = mysqli_insert_id($connect);
            echo "<script>alert('Booking Successful! Redirecting to transaction details.');</script>";
            echo "<script>document.location.href='receipt.php?transaction_id=$transaction_id';</script>";
        } else {
            echo "Error: " . $transaction_sql . "<br>" . mysqli_error($connect);
        }
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($connect);
    }
}

mysqli_close($connect);
?>
