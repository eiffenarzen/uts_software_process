<?php
require "../../config/koneksi.php";
require "../../includes/auth_helper.php";
require "../../includes/header.php";

/** @var mysqli $conn */

if (!isset($_SESSION['role'])) {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SESSION['role'] != "Kasir") {
    die("Akses ditolak");
}

if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {

    echo "
    <script>
        alert('Keranjang kosong');
        window.location = 'transaksi.php';
    </script>
    ";

    exit;
}

$bayar = $_POST['bayar'];

$total = 0;

foreach ($_SESSION['cart'] as $c) {
    $total += $c['subtotal'];
}

if ($bayar < $total) {

    echo "
    <script>
        alert('Uang bayar kurang');
        window.location = 'transaksi.php';
    </script>
    ";

    exit;
}

/* ================= SIMPAN PENJUALAN ================= */

$nota = "TRX" . date("YmdHis");

mysqli_query($conn, "
    INSERT INTO t_penjualan(
        nomor_nota,
        tgl_transaksi,
        id_user,
        total_bayar
    ) VALUES (
        '$nota',
        NOW(),
        '{$_SESSION['id_user']}',
        '$total'
    )
");

$id_penjualan = mysqli_insert_id($conn);

/* ================= DETAIL ================= */

foreach ($_SESSION['cart'] as $c) {

    mysqli_query($conn, "
        INSERT INTO t_penjualan_detail(
            id_penjualan,
            id_produk,
            qty,
            subtotal
        ) VALUES (
            '$id_penjualan',
            '{$c['id_produk']}',
            '{$c['qty']}',
            '{$c['subtotal']}'
        )
    ");

    mysqli_query($conn, "
        UPDATE m_produk
        SET stok = stok - {$c['qty']}
        WHERE id_produk = '{$c['id_produk']}'
    ");

    mysqli_query($conn, "
        INSERT INTO t_log_stok(
            id_produk,
            jumlah,
            tipe,
            keterangan
        ) VALUES (
            '{$c['id_produk']}',
            '{$c['qty']}',
            'Keluar',
            'Transaksi Penjualan'
        )
    ");
}

$kembalian = $bayar - $total;
unset($_SESSION['cart']);
?>

<style>
    .success-wrapper {
        min-height: calc(100vh - 140px);
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 30px 15px;
    }

    .card-success {
        width: 100%;
        max-width: 520px;
        background: white;
        padding: 40px 30px;
        border-radius: 28px;
        border: 1px solid #dcfce7;
        box-shadow: 0 15px 35px rgba(22, 163, 74, 0.1);
        text-align: center;
    }

    .success-icon {
        width: 100px;
        height: 100px;
        margin: auto auto 25px;
        border-radius: 50%;
        background: linear-gradient(135deg, #22c55e, #16a34a);
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
        font-size: 48px;
        font-weight: bold;
        box-shadow: 0 12px 25px rgba(22, 163, 74, 0.2);
    }

    .card-success h1 {
        font-size: 32px;
        color: #166534;
        margin-bottom: 10px;
    }

    .card-success p {
        color: #64748b;
        margin-bottom: 25px;
    }

    .info-box {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        padding: 22px;
        border-radius: 20px;
        text-align: left;
    }

    .info-box p {
        margin-bottom: 12px;
        color: #166534;
        font-size: 15px;
    }

    .info-box p:last-child {
        margin-bottom: 0;
    }

    .btn-group {
        margin-top: 30px;
        display: flex;
        gap: 15px;
    }

    .btn-action {
        flex: 1;
        text-decoration: none;
        padding: 14px;
        border-radius: 16px;
        color: white;
        font-weight: bold;
        transition: 0.3s;
        text-align: center;
    }

    .btn-back {
        background: #22c55e;
    }

    .btn-print {
        background: #166534;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        opacity: 0.9;
    }

    @media (max-width: 768px) {

        .card-success {
            padding: 30px 20px;
            border-radius: 22px;
        }

        .card-success h1 {
            font-size: 26px;
        }

        .btn-group {
            flex-direction: column;
        }

        .btn-action {
            width: 100%;
        }
    }
</style>

<div class="success-wrapper">

    <div class="card-success">

        <div class="success-icon">
            ✔
        </div>

        <h1>Transaksi Berhasil</h1>

        <p>Pembayaran berhasil diproses</p>

        <div class="info-box">

            <p>
                <strong>No Nota :</strong>
                <?= $nota; ?>
            </p>

            <p>
                <strong>Total :</strong>
                Rp <?= number_format($total); ?>
            </p>

            <p>
                <strong>Bayar :</strong>
                Rp <?= number_format($bayar); ?>
            </p>

            <p>
                <strong>Kembalian :</strong>
                Rp <?= number_format($kembalian); ?>
            </p>

        </div>

        <div class="btn-group">

            <a href="transaksi.php" class="btn-action btn-back">
                Kembali
            </a>

            <a href="nota.php?id=<?= $id_penjualan; ?>" class="btn-action btn-print">
                Cetak Nota
            </a>

        </div>

    </div>

</div>

<?php require "../../includes/footer.php"; ?>
