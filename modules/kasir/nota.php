<?php
require "../../config/koneksi.php";
require "../../includes/auth_helper.php";
require "../../includes/header.php";

/** @var mysqli $conn */

if (!isset($_SESSION['role'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id = $_GET['id'];

$penjualan = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT
            t_penjualan.*,
            m_user.username
        FROM t_penjualan
        INNER JOIN m_user
            ON t_penjualan.id_user = m_user.id_user
        WHERE id_penjualan = '$id'
    ")
);

$detail = mysqli_query($conn, "
    SELECT
        t_penjualan_detail.*,
        m_produk.nama_produk
    FROM t_penjualan_detail
    INNER JOIN m_produk
        ON t_penjualan_detail.id_produk = m_produk.id_produk
    WHERE id_penjualan = '$id'
");
?>

<style>
    body {
        background: linear-gradient(
            135deg,
            #dcfce7,
            #f0fdf4
        );
    }

    .nota-wrapper {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 30px 15px;
        min-height: 100vh;
    }

    .struk {
        width: 360px;
        background: white;
        padding: 25px;
        border-radius: 24px;
        border: 1px solid #dcfce7;
        box-shadow: 0 15px 40px rgba(34, 197, 94, 0.15);
    }

    .header-nota {
        text-align: center;
        margin-bottom: 20px;
    }

    .header-nota h1 {
        font-size: 32px;
        color: #15803d;
        margin-bottom: 8px;
        font-weight: 800;
        letter-spacing: 1px;
    }

    .header-nota p {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 3px;
    }

    .info {
        margin-bottom: 15px;
        font-size: 14px;
        color: #334155;
    }

    .info p {
        margin-bottom: 8px;
    }

    .line {
        border-top: 2px dashed #86efac;
        margin: 18px 0;
    }

    .item {
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #f1f5f9;
    }

    .item-top {
        display: flex;
        justify-content: space-between;
        font-size: 15px;
        font-weight: 700;
        color: #166534;
    }

    .item-bottom {
        display: flex;
        justify-content: space-between;
        font-size: 13px;
        margin-top: 5px;
        color: #64748b;
    }

    .total {
        margin-top: 15px;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 15px;
        color: #334155;
    }

    .grand-total {
        font-size: 22px;
        font-weight: 800;
        color: #16a34a;
    }

    .footer-nota {
        text-align: center;
        margin-top: 25px;
        font-size: 13px;
        color: #64748b;
        line-height: 1.7;
    }

    .btn-area {
        text-align: center;
        margin-top: 25px;
    }

    .btn-nota {
        display: inline-block;
        padding: 12px 22px;
        background: linear-gradient(
            135deg,
            #22c55e,
            #16a34a
        );
        color: white;
        text-decoration: none;
        border-radius: 14px;
        font-size: 14px;
        font-weight: bold;
        margin: 5px;
        transition: 0.3s;
        box-shadow: 0 8px 20px rgba(34, 197, 94, 0.25);
    }

    .btn-nota:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 25px rgba(34, 197, 94, 0.35);
    }

    @media print {

        header,
        footer,
        .sidebar,
        .topbar,
        .btn-area {
            display: none !important;
        }

        body {
            background: white;
        }

        .nota-wrapper {
            padding: 0;
        }

        .struk {
            box-shadow: none;
            border: none;
            border-radius: 0;
            width: 100%;
        }
    }

    @media (max-width: 480px) {

        .nota-wrapper {
            padding: 15px;
        }

        .struk {
            width: 100%;
            padding: 20px;
            border-radius: 20px;
        }

        .header-nota h1 {
            font-size: 28px;
        }

        .btn-nota {
            width: 100%;
        }
    }
</style>

<div class="nota-wrapper">

    <div>

        <div class="struk">

            <!-- ================= HEADER ================= -->

            <div class="header-nota">
                <h1>SI-KASIR</h1>
                <p>Jl. Raya Kasir No. 123</p>
                <p>Telp: 0812-3456-7890</p>
            </div>

            <div class="line"></div>

            <!-- ================= INFO ================= -->

            <div class="info">
                <p>No Nota : <?= $penjualan['nomor_nota']; ?></p>

                <p>
                    Tanggal :
                    <?= date('d-m-Y H:i', strtotime($penjualan['tgl_transaksi'])); ?>
                </p>

                <p>Kasir : <?= $penjualan['username']; ?></p>
            </div>

            <div class="line"></div>

            <!-- ================= ITEM ================= -->

            <?php
            $total = 0;

            while ($d = mysqli_fetch_assoc($detail)) {

                $total += $d['subtotal'];
            ?>

            <div class="item">

                <div class="item-top">
                    <span><?= $d['nama_produk']; ?></span>

                    <span>
                        Rp <?= number_format($d['subtotal']); ?>
                    </span>
                </div>

                <div class="item-bottom">
                    <span>
                        <?= $d['qty']; ?> x
                        Rp <?= number_format($d['subtotal'] / $d['qty']); ?>
                    </span>
                </div>

            </div>

            <?php } ?>

            <div class="line"></div>

            <!-- ================= TOTAL ================= -->

            <div class="total">

                <div class="total-row grand-total">

                    <span>Total</span>

                    <span>
                        Rp <?= number_format($total); ?>
                    </span>

                </div>

            </div>

            <div class="line"></div>

            <!-- ================= FOOTER ================= -->

            <div class="footer-nota">
                <p>Terima Kasih 🙏</p>
                <p>Barang yang sudah dibeli</p>
                <p>tidak dapat dikembalikan</p>
            </div>

        </div>

        <!-- ================= BUTTON ================= -->

        <div class="btn-area">

            <a href="transaksi.php" class="btn-nota">
                Kembali
            </a>

            <a href="#" onclick="window.print()" class="btn-nota">
                Print
            </a>

        </div>

    </div>

</div>

<?php require "../../includes/footer.php"; ?>
