<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function checkLogin() {

    if (!isset($_SESSION['id_user'])) {

        header("Location: ../auth/login.php");
        exit;
    }
}

function adminOnly()
{
    if (!isset($_SESSION['role'])) {

        header("Location: ../auth/login.php");
        exit;
    }

    if ($_SESSION['role'] != "Admin") {

        die("Akses ditolak");
    }
}

function kasirOnly()
{
    if (!isset($_SESSION['role'])) {

        header("Location: ../auth/login.php");
        exit;
    }

    if ($_SESSION['role'] != "Kasir") {

        die("Akses ditolak");
    }
}
?>
