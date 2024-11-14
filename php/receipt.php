<?php
// receipt.php
session_start();

if (!isset($_SESSION['uid'])) {
    echo "<script>alert('Please log in to view your receipt.');</script>";
    echo "<script>document.location.href='authorization.php';</script>";
    exit();
}

$host = "localhost";
$user = "nis";
$pass = "!nis2024";
$dbname = "restaurant";

$connect = mysqli_connect($host, $user, $pass, $dbname);

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

$transaction_id = mysqli_real_escape_string($connect, $_GET['transaction_id']);

// Получение информации о транзакции
$transaction_query = "SELECT * FROM transactions WHERE id = '$transaction_id' AND username = '{$_SESSION['uid']}'";
$transaction_result = mysqli_query($connect, $transaction_query);
$transaction = mysqli_fetch_assoc($transaction_result);

if (!$transaction) {
    echo "<script>alert('Transaction not found.');</script>";
    echo "<script>document.location.href='index.php';</script>";
    exit();
}

// Получение информации о бронировании
$booking_query = "SELECT * FROM booking WHERE bid = '{$transaction['reservation_id']}'";
$booking_result = mysqli_query($connect, $booking_query);
$booking = mysqli_fetch_assoc($booking_result);

mysqli_close($connect);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt</title>
    <link rel="stylesheet" href="../css/main.css">
</head>
<body>

<div class="receipt-container">
    <h1>Receipt for Transaction #<?php echo htmlspecialchars($transaction['id']); ?></h1>
    <h2>Reservation Details</h2>
    <p><strong>Name:</strong> <?php echo htmlspecialchars($booking['bname']); ?></p>
    <p><strong>Guests:</strong> <?php echo htmlspecialchars($booking['bguests']); ?></p>
    <p><strong>Table Number:</strong> <?php echo htmlspecialchars($booking['btable']); ?></p>
    <p><strong>Time:</strong> <?php echo htmlspecialchars($booking['btime']); ?></p>
    <p><strong>Phone:</strong> <?php echo htmlspecialchars($booking['bphone']); ?></p>

    <h2>Transaction Details</h2>
    <p><strong>Amount:</strong> $<?php echo htmlspecialchars($transaction['amount']); ?></p>
    <p><strong>Status:</strong> <?php echo htmlspecialchars($transaction['status']); ?></p>

    <p><a href="payment.php?transaction_id=<?php echo $transaction_id; ?>">Proceed to Payment (Feature Coming Soon)</a></p>
</div>

</body>
</html>
