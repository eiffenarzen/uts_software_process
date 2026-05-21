<?php
require "../../config/koneksi.php";
require "../../includes/auth_helper.php";

/** @var mysqli $conn */

/* ================= CEK LOGIN ================= */

if (!isset($_SESSION['role'])) {
    header("Location: ../auth/login.php");
    exit;
}

/* ================= CEK ROLE ================= */

if ($_SESSION['role'] != "Admin") {
    die("Akses ditolak");
}

/* ================= VALIDASI ID ================= */

if (!isset($_GET['id'])) {

    echo "
    <script>
        alert('ID produk tidak ditemukan!');
        window.location = 'produk.php';
    </script>
    ";

    exit;
}

$id = (int) $_GET['id'];

/* ================= CEK PRODUK ================= */

$cekProduk = mysqli_query($conn, "
    SELECT * FROM m_produk
    WHERE id_produk = '$id'
");

if (mysqli_num_rows($cekProduk) == 0) {

    echo "
    <script>
        alert('Produk tidak ditemukan!');
        window.location = 'produk.php';
    </script>
    ";

    exit;
}

/* ================= VALIDASI TRANSAKSI ================= */

$cek = mysqli_query($conn, "
    SELECT * FROM t_penjualan_detail
    WHERE id_produk = '$id'
");

if (mysqli_num_rows($cek) > 0) {

    echo "
    <script>
        alert('Produk sudah pernah dipakai transaksi dan tidak bisa dihapus!');
        window.location = 'produk.php';
    </script>
    ";

    exit;
}

/* ================= HAPUS LOG STOK ================= */

mysqli_query($conn, "
    DELETE FROM t_log_stok
    WHERE id_produk = '$id'
");

/* ================= HAPUS PRODUK ================= */

mysqli_query($conn, "
    DELETE FROM m_produk
    WHERE id_produk = '$id'
");

/* ================= SUCCESS ================= */

echo "
<script>
    alert('Produk berhasil dihapus!');
    window.location = 'produk.php';
</script>
";
?>
