<?php
session_start();

require "config/koneksi.php";

/** @var mysqli $conn */

if (!isset($_SESSION['role'])) {
    header("Location: auth/login.php");
    exit;
}

if ($_SESSION['role'] == "Admin") {
    header("Location: admin/dashboard.php");
    exit;
}

if ($_SESSION['role'] == "Kasir") {
    header("Location: kasir/transaksi.php");
    exit;
}

session_destroy();

header("Location: auth/login.php");
exit;
?>
