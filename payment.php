<?php
// payment.php
session_start();

if (!isset($_SESSION['uid'])) {
    echo "<script>alert('Please log in to proceed with payment.');</script>";
    echo "<script>document.location.href='authorization.php';</script>";
    exit();
}

echo "<h1>Payment Page (Coming Soon)</h1>";
echo "<p>Transaction ID: " . htmlspecialchars($_GET['transaction_id']) . "</p>";
echo "<p>This page will allow you to proceed with the payment in the future.</p>";
?>
