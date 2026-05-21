<?php

require "../../config/koneksi.php";
require "../../includes/auth_helper.php";

/** @var mysqli $conn */

if (empty($_POST['username']) || empty($_POST['password'])) {

    $_SESSION['error'] = "Username dan Password wajib diisi!";

    header("Location: login.php");
    exit;
}

$username = trim($_POST['username']);
$password = trim($_POST['password']);

/* ================= LOGIN ATTEMPT ================= */

if (!isset($_SESSION['login_attempt'])) {
    $_SESSION['login_attempt'] = 0;
}

if (!isset($_SESSION['last_attempt'])) {
    $_SESSION['last_attempt'] = time();
}

if ($_SESSION['login_attempt'] >= 5) {

    $wait = time() - $_SESSION['last_attempt'];

    if ($wait < 300) {

        $_SESSION['error'] =
            "Terlalu banyak percobaan, tunggu 5 menit.";

        header("Location: login.php");
        exit;

    } else {

        $_SESSION['login_attempt'] = 0;
    }
}

/* ================= LOGIN ================= */

$query = mysqli_query($conn, "
    SELECT * FROM m_user
    WHERE username = '$username'
");

$data = mysqli_fetch_assoc($query);

if ($data) {

    if (password_verify($password, $data['password'])) {

        $_SESSION['id_user']  = $data['id_user'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['role']     = $data['role'];

        $_SESSION['login_attempt'] = 0;

        if ($data['role'] == "Admin") {

            header("Location: ../admin/dashboard.php");

        } else {

            header("Location: ../kasir/transaksi.php");
        }

        exit;

    } else {

        $_SESSION['login_attempt']++;
        $_SESSION['last_attempt'] = time();

        $_SESSION['error'] =
            "Username atau Password salah!";

        header("Location: login.php");
        exit;
    }

} else {

    $_SESSION['login_attempt']++;
    $_SESSION['last_attempt'] = time();

    $_SESSION['error'] =
        "Username atau Password salah!";

    header("Location: login.php");
    exit;
}
?>
