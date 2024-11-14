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

    $sql = "INSERT INTO booking (uid, bname, bguests, btable, btime, bphone) VALUES ('$uid', '$bname', '$bguests', '$btable', '$btime', '$bphone')";

    if (mysqli_query($connect, $sql)) {
        echo "<script>alert('Booking Successful!');</script>";
        echo "<script>document.location.href='../index.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($connect);
    }
}

if (isset($_GET['delete_booking'])) {
    $bid = $_GET['delete_booking'];
    mysqli_query($connect, "DELETE FROM booking WHERE bid='$bid'");
    echo "<script>alert('Booking Deleted');</script>";
    echo "<script>document.location.href='../admin.php';</script>";
}

mysqli_close($connect);
?>