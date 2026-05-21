<?php
require "../../config/koneksi.php";
require "../../includes/auth_helper.php";

/** @var mysqli $conn */

if($_SESSION['role'] != "Admin"){
    die("Akses ditolak");
}

$id = $_GET['id'];

/* ================= CEK DIRI SENDIRI ================= */

if($id == $_SESSION['id_user']){

    echo "
    <script>
    alert('Admin tidak bisa menghapus akun sendiri');
    window.location='user.php';
    </script>
    ";

    exit;
}

/* ================= CEK TRANSAKSI ================= */

$cekTransaksi = mysqli_query($conn,"
SELECT COUNT(*) total
FROM t_penjualan
WHERE id_user='$id'
");

$dataTransaksi = mysqli_fetch_assoc($cekTransaksi);

if($dataTransaksi['total'] > 0){

    echo "
    <script>
    alert('Kasir memiliki data transaksi dan tidak bisa dihapus!');
    window.location='user.php';
    </script>
    ";

    exit;
}

/* ================= HAPUS ================= */

mysqli_query($conn,"
DELETE FROM m_user
WHERE id_user='$id'
");

echo "
<script>
alert('User berhasil dihapus');
window.location='user.php';
</script>
";
?>
